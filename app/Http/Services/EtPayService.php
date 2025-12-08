<?php

namespace App\Http\Services;


use App\Exceptions\ApiException;
use App\Models\V1\ChatUser;
use App\Models\V1\DigitalCurrencyOrder;
use App\Models\V1\OrderModel;
use App\Models\V1\UserWallet;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;

class EtPayService
{
    private string $appId;
    private string $appSecret;
    private string $baseUrl;

    // 构造函数
    public function __construct()
    {
        $this->appId     = Env::get('ETPAY_APPID');
        $this->appSecret = Env::get('ETPAY_APPKEY');
        $this->baseUrl   = Env::get('ETPAY_URL');
    }

    /**
     * 公共参数
     * @return array
     */
    private function common(): array
    {
        return [
            'appId'     => $this->appId,
            'timestamp' => time() * 1000,
            'nonce'     => mt_rand(100000, 999999),
        ];
    }

    /**
     * 签名
     * @param array $common
     * @param array $data
     * @return string
     */
    private function sign(array $common, array $data = []): string
    {
        $params = $common;
        if ($data) $params = array_merge($params, $data);
        $str = '';
        ksort($params);
        foreach ($params as $key => $value) {
            $str .= $key . $value;
        }
        return md5($str . $this->appSecret);
    }

    /**
     * 发送请求
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function request(string $url, array $data = []): mixed
    {
        $url = $this->baseUrl . $url;
        try {
            $params         = $this->common();
            $params['sign'] = $this->sign($params, $data);
            $params         = array_merge($params, $data);
            $result         = Http::asJson()->post($url, $params)->json();
            if ($result['code'] != 200) {
                Log::error("支付接口错误:msg:" . $result['msg'] . ";code:" . $result['code']);
                throw new ApiException('支付接口错误');
            }
            return $result['data'];
        } catch (Exception $e) {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            throw new ApiException('支付接口错误');
        }
    }

    /**
     * 创建用户
     * @throws Exception|InvalidArgumentException
     */
    public function createdUser($userId): array
    {
        $user = ChatUser::find($userId);
        if (!$user['pay_id']) {
            $url          = '/open-api/merchant/user/create';
            $res          = $this->request($url, ['userId' => (string)$userId]);
            $user->pay_id = $res['userId'];
            $user->save();
            // 设置交易密码
            $this->setPayPwd($userId, $user->pay_password);
        }
        // 创建地址
        $res = $this->getCurrencyInfo('USDT');
        foreach ($res['protocolTypeList'] as $item) {
            // 判断地址是否存在
            $address = UserWallet::where('user_id', $userId)->where('currency_type', $res['currencyType'])->where('protocol_type', $item['protocolType'])->first();
            if ($address) {
                continue;
            }
            $this->addAddress($userId, $res['currencyType'], $item['protocolType']);
        }
        return $res;
    }

    /**
     * 设置支付密码
     * @param $userId
     * @param $pwd
     * @throws Exception
     */
    public function setPayPwd($userId, $pwd)
    {
        $user   = ChatUser::find($userId);
        $url    = '/open-api/merchant/user/setPaymentPwd';
        $params = [
            'userId'          => $user->pay_id,
            'paymentPwd'      => $pwd,
            'againPaymentPwd' => $pwd,
        ];
        $this->request($url, $params);
    }

    /**
     * 修改支付密码
     * @param $userId
     * @throws Exception
     */
    public function updatePayPwd($userId): void
    {
        $user   = ChatUser::find($userId);
        $url    = '/open-api/merchant/user/updatePaymentPwd';
        $params = [
            'userId'             => $user->pay_id,
            'paymentPwd'         => $user->pay_password,
            'newPaymentPwd'      => $user->pay_password,
            'againNewPaymentPwd' => $user->pay_password,
        ];
        $this->request($url, $params);
    }

    /**
     * 重置支付密码
     * @param $userId
     * @throws Exception
     */
    public function resetPayPwd($userId): void
    {
        $user   = ChatUser::find($userId);
        $url    = '/open-api/merchant/user/resetPaymentPwd';
        $params = [
            'userId'             => $user->pay_id,
            'newPaymentPwd'      => $user->pay_password,
            'againNewPaymentPwd' => $user->pay_password,
        ];
        $this->request($url, $params);
    }

    /**
     * 添加地址
     * @param $userId
     * @param $currencyType
     * @param $protocolType
     * @return bool
     * @throws Exception
     */
    public function addAddress($userId, $currencyType, $protocolType): bool
    {
        $user   = ChatUser::find($userId);
        $url    = '/open-api/merchant/wallet/address/add';
        $params = [
            'userId'       => $user->pay_id,
            'currencyType' => $currencyType,//币种类型
            'protocolType' => $protocolType,//协议类型
        ];
        $res    = $this->request($url, $params);
        UserWallet::create(
            [
                'user_id'       => $userId,
                'address'       => $res['walletAddress'],
                'currency_type' => $currencyType,
                'protocol_type' => $protocolType,
            ]
        );
        return true;
    }


    /**
     * 地址列表
     * @param $userId
     * @param $currencyType
     * @param $protocolType
     * @return array
     * @throws Exception
     */
    public function listAddress($userId, $currencyType, $protocolType): array
    {
        $user   = ChatUser::find($userId);
        $url    = '/open-api/merchant/wallet/address/list';
        $params = [
            'userId'       => $user->pay_id,
            'currencyType' => $currencyType,
            'protocolType' => $protocolType,
        ];

        $res = $this->request($url, $params);

        // 查询用户钱包地址信息
        $walletList = UserWallet::where('user_id', $userId)
            ->where('currency_type', $currencyType)
            ->where('protocol_type', $protocolType)
            ->select(['id, address'])
            ->get()->keyBy('address'); // 以地址为键获取ID和地址

        // 存储远程存在的地址，用于后续对比
        foreach ($res as &$item) {
            $item['user_id'] = $userId;
            unset($item['userId']);

            if (isset($walletList[$item['address']])) {
                $walletItem = $walletList[$item['address']];
                $item['id'] = $walletItem['id'];

                // 更新余额和入账次数
                UserWallet::where('id', $walletItem['id'])->update([
                    'balance'  => $item['balance'],
                    'in_times' => $item['inCount'],
                ]);
                // 从本地列表中移除，表示已处理
                unset($walletList[$item['address']]);
            } else {
                // 新增远程存在但本地不存在的地址
                UserWallet::create([
                    'user_id'       => $userId,
                    'address'       => $item['address'],
                    'currency_type' => $currencyType,
                    'protocol_type' => $protocolType,
                    'balance'       => $item['balance'],
                    'in_times'      => $item['inCount'],
                ]);
            }
        }

        // 删除本地存在但远程不存在的地址
        if (!empty($walletList)) {
            $deleteIds = $walletList->pluck('id')->toArray();
            UserWallet::where('id', 'in', $deleteIds)->delete();
        }
        return $res;
    }

    /**
     * 获取币种信息
     * @param $currencyType
     * @return array
     * @throws Exception|InvalidArgumentException
     */
    public function getCurrencyInfo($currencyType): array
    {
        $key = 'currency_info_' . $currencyType;
        if (Cache::store('redis')->has($key)) {
            return Cache::store('redis')->get($key);
        }

        $url    = '/open-api/merchant/wallet/getCurrencyInfo';
        $params = [
            'currencyType' => $currencyType,//币种类型
        ];
        $data   = $this->request($url, $params);
        foreach ($data['protocolTypeList'] as $k => $item) {
            if (!in_array($item['protocolType'], UserWallet::TYPE_GROUP)) {
                unset($data['protocolTypeList'][$k]);
            }
        }
        if ($data) Cache::store('redis')->set($key, $data, 3600 * 24);
        return $data;
    }

    /**
     * 验签
     * @param $params
     * @return bool
     */
    private function verifySign($params): bool
    {
        $sign = $params['sign'];
        unset($params['sign']);
        $str = '';
        ksort($params);
        foreach ($params as $key => $value) {
            $str .= $key . $value;
        }
        return $sign == md5($str . $this->appSecret);
    }

    /**
     * 回调处理
     * @param $params
     * @return bool
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function notify($params): bool
    {
        // 1. 确保签名正确
        if (!$this->verifySign($params)) {
            Log::error('签名错误: ' . json_encode($params));
            return false;
        }

        $orderNo   = $params['orderNo'];
        $orderType = $params['orderType'];
        $userId    = $params['userId'];// 支付平台会员ID
        $redis     = Cache::store('redis');
        $lockKey   = "etpay:notify:lock:" . $orderNo;
        $requestId = uniqid();
        $return    = false;
        Db::beginTransaction();
        try {
            $user = ChatUser::where('pay_id', $userId)->first();
            if (!$user) {
                Log::error('用户不存在: ' . $userId);
                return false;
            }
            // 尝试获取锁（设置10秒过期，防止死锁）
            if (!$redis->set($lockKey, $requestId, ['nx', 'ex' => 10])) {
                Log::error('订单处理中，拒绝重复请求: ' . $orderNo);
                return false;
            }
            switch ($orderType) {//1充币,2提币
                case 1:
                    $this->handlePayment($user, $params);
                    break;
                case 2:

                    break;
            }

            $return = true;
        } catch (Exception $e) {
            Log::error('回调处理异常: ' . $e->getMessage());
        } finally {
            // 释放锁（只有当前请求持有的锁才能释放）
            if ($redis->get($lockKey) == $requestId) {
                $redis->forget($lockKey);
            }
            if ($return) {
                Db::commit();
            } else {
                Db::rollback();
            }
        }
        return $return;
    }

    /**
     * 处理充值
     * @param $user
     * @param $params
     * @return void
     * @throws ApiException
     */
    private function handlePayment($user, $params): void
    {
        // 1. 查询订单
        $order      = OrderModel::where('user_id', $user->id)
            ->where('state', OrderModel::STATUS_PAYING)
            ->whereTime('expiretime', '<=', time())
            ->first();
        $pay_status = true;
        if (($order && $order->pay_price > $params['number']) || !$order) {
            $pay_status = false;
        }
        if ($pay_status) {
            $orderId = $order->id;
            if ($params['status'] == 'success') {
                OrderModel::pay_notify($order->id);
            }
        }
        DigitalCurrencyOrder::addLog($user, $orderId ?? 0, $params);
    }


}
