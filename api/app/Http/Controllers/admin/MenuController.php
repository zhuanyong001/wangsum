<?php

namespace App\Http\Controllers\admin;

use App\Dao\MenuDao;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class MenuController extends Controller
{
    protected $MenuDao;

    public function __construct(MenuDao $MenuDao)
    {
        $this->MenuDao = $MenuDao;
    }
    //
    public function getMenus()
    {
        $menus =  $this->MenuDao->getMenus([]);
        return $menus;
    }


    public function saveMenus(Request $request, $id = null)
    {
        $menus = $request->all();
        Menu::updateOrCreate(['id' => $id ?? 0], $menus);
        return $this->success();
    }
    public function show(Request $request, $id = null)
    {
        $menu = Menu::find($id);
        return $this->success($menu);
    }

    public function deleteMenus(Request $request, $id = null)
    {
        Menu::destroy($id);
        return $this->success();
    }


    public function role_list()
    {
        $list = Role::paginate(15);
        return $this->success(
            [
                'list' => $list->items(),
                'total' => $list->total()
            ]
        );
    }

    public function saveRole(Request $request, $id = null)
    {
        $save = Role::updateOrCreate(
            [
                'id' => $id ?? 0
            ],
            $request->all()
        );
        return $save ? $this->success() : $this->fail();
    }

    public function saveAdmin($id, Request $request)
    {
        // 验证请求数据
        $validatedData = $request->validate([
            'username' => 'required',
            // 'role_id' => 'required|exists:roles,id', // 确保 role_id 在 roles 表中存在
            'is_super' => 'sometimes|int',

        ]);
        if ($request->get('password')) {
            // 对密码进行哈希处理
            $validatedData['password'] = bcrypt($request->get('password'));
        }

        // 检查是否提供了 user_account 并获取关联的 user_id
        if ($request->get('user_account', '')) {
            $validatedData['user_account'] = $request->get('user_account');
            $userId = User::where('name', $validatedData['user_account'])->value('id');
            if (!$userId) {
                return $this->fail('关联用户不存在'); // 关联用户不存在
            }
            $validatedData['user_id'] = $userId;
        }
        // 使用 updateOrCreate 方法保存或更新角色
        $save = Admin::updateOrCreate(
            ['id' => $id],
            $validatedData
        );

        // 返回成功或失败响应
        return $save ? $this->success() : $this->fail();
    }

    //账号列表
    public function adminList(Request $request)
    {
        //排除admin账号
        $size = $request->get('size', 15);
        $data = Admin::paginate($size);
        $list = $data->items();
        foreach ($list as $item) {
            $item->user_account = User::where('id', $item->user_id)->value('tron_address');
        }

        return $this->success(
            [
                'list' => $list,
                'total' => $data->total()
            ]
        );
    }

    /**
     * 生成谷歌密钥
     */
    public function generateGoogle2faSecret(Request $request)
    {
        $google2fa = new Google2FA();
        $admin_id = $request->get('id');
        $admin = Admin::findOrfail($admin_id);
        $secret = $google2fa->generateSecretKey();
        $admin->google2fa_secret = $secret;
        $admin->save();
        //删除token

        return $this->success(['secret' => $secret]);
    }
    /*
    *清理Google2faSecret
    */
    public function clearGoogle2faSecret(Request $request)
    {
        $admin_id = $request->get('id');
        $admin = Admin::findOrfail($admin_id);
        $admin->google2fa_secret = null;
        $admin->tokens()->delete();
        $admin->save();
        return $this->success();
    }

    /**
     * 生成谷歌验证器二维码
     */
    public function getGoogle2faQrCode(Request $request)
    {
        $admin_id = $request->get('id');
        $admin = Admin::findOrfail($admin_id);
        if (!$admin->google2fa_secret) {
            return $this->fail('请先生成谷歌密钥');
        }
        $google2fa = new Google2FA();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'DragonflySwap Admin',
            $admin->username,
            $admin->google2fa_secret
        );

        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);
        return $this->success([
            'qr_code_svg' => $qrCodeSvg,
            'secret' => $admin->google2fa_secret
        ]);
    }
}
