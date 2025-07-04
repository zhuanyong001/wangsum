<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DepositOrder;
use App\Models\UserAssetLog;
use App\Models\WithdrawalOrder;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * 充值记录
     */
    public function rechargeList(Request $request)
    {
        $size = $request->get('size', 20);
        $query = DepositOrder::with(['user', 'currency'])->WithAdminAuth();

        $status = $request->get('status');

        if ($status) {
            $query->where('status', $status);
        }
        $username = $request->get('username');
        if ($username) {
            $query->WithUsername($username);
        }
        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $address = $request->get('address');
        if ($address) {
            $query->where(function ($query) use ($address) {
                $query->where('source_address', 'like', "%$address%")
                    ->orWhere('destination_address', 'like', "%$address%");
            });
        }
        $currency = $request->get('currency');
        if ($currency) {
            $query->where('currency', $currency);
        }

        $date = $request->get('date', []);
        if (count($date) == 2) {
            $query->whereBetween('created_at', $date);
        }

        $data = $query->orderByDesc('id')->paginate($size);
        $data->getCollection()->each(function ($item) {
            $item->user->makeVisible('remark');
        });
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }
    public function withdrawalList(Request $request)
    {
        $size = $request->get('size', 20);
        $query = WithdrawalOrder::with(['user', 'currency'])->WithAdminAuth();
        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $username = $request->get('username');
        if ($username) {
            $query->WithUsername($username);
        }
        $currency = $request->get('currency');
        if ($currency) {
            $query->where('currency', $currency);
        }

        $status = $request->get('status');
        if ($status) {
            $query->where('status', $status);
        }
        $address = $request->get('address');
        if ($address) {
            $query->where('destination_address', 'like', "%$address%");
        }
        $date = $request->get('date', []);
        if (count($date) == 2) {
            $query->whereBetween('created_at', $date);
        }
        $data = $query->orderByDesc('id')->paginate($size);
        $data->getCollection()->each(function ($item) {
            $item->user->makeVisible('remark');
        });
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }

    public function UserAssetLogList(Request $request)
    {
        $size = $request->get('size', 20);
        $query = UserAssetLog::with(['userAsset', 'user'])->WithAdminAuth();

        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $type = $request->get('type', -1);
        if ($type != -1) {
            $query->where('type', $type);
        }
        $date = $request->get('date', []);
        if (count($date) == 2) {
            $query->whereBetween('created_at', $date);
        }
        if ($request->get('currency_id')) {
            $query->whereHas('userAsset', function ($query) use ($request) {
                $query->where('currency_id', $request->get('currency_id'));
            });
        }




        $data = $query->orderByDesc('id')->paginate($size);
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }
}
