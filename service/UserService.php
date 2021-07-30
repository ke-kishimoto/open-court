<?php
namespace service;
use api\LineApi;
use dao\UsersDao;

class UserService
{
    public function lineLogin($code)
    {
        $lineApi = new LineApi();
        // アクセストークン取得
        $response = $lineApi->getAccessToken($code);
        $accessToken = $response->access_token;
        $refreshToken = $response->refresh_token;
        $idToken = $response->id_token;

        // IDの検証
        $response = $lineApi->tokenVerify($idToken);

        // プロフィール取得
        // $response = $lineApi->getLineProfile($accessToken);
        // $response = $lineApi->getLineProfileByCode($code);

        // DBにそのLINE IDのユーザーがいるかどうか確認
        $userDao = new UsersDao();
        $user = $userDao->getUserByLineId($response->sub);

        if($user) {
            // 存在する場合
            if(empty($user['name'])) {
                $user->name = $response->name;
                $user->adminFlg = 0;
                $user->lineId = $response->sub;
                $user->accessToken = $accessToken;
                $user->refreshToken = $refreshToken;
                $userDao->update($user);
            } else {
                return $user;
            }
        } else {
            // 存在しない場合
            // $user = new Users();
            // $user->name = $response->name;
            // $user->adminFlg = 0;
            // $user->lineId = $response->sub;
            // $user->accessToken = $accessToken;
            // $user->refreshToken = $refreshToken;
            $user = [];
            $user['name'] = $response->name;
            $user['admin_flg'] = 0;
            $user['line_id'] = $response->sub;
            $user['access_token'] = $accessToken;
            $user['refresh_token'] = $refreshToken;
            $userDao->insert($user);
        }

        // 再取得
        $user = $userDao->getUserByLineId($response->sub);
        return $user;

    }
}
