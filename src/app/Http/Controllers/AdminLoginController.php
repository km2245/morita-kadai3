<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        // 入力された email と password を取得
        $credentials = $request->only('email', 'password');

        // role = admin を追加して管理者だけログイン可能にする
        $credentials['role'] = 'admin';

        $request->session()->regenerate();

        // ログイン判定
        if (!Auth::attempt($credentials)) {

            // ログイン失敗時
            return back()->withErrors([
                'email' => '管理者情報が正しくありません。',
            ]);
        }

        // 成功したら管理画面へ
        return redirect()->route('admin.attendance.list');
    }
}
