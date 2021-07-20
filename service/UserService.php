<?php
namespace service;
use api\LineApi;
use dao\UsersDao;
use entity\Users;

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

        // 存在する場合はそのままreturn
        if($user) {
            $user['new'] = false;
            return $user;
        }
        $user = new Users();
        $user->name = $response->name;
        $user->adminFlg = 0;
        $user->lineId = $response->sub;
        $user->accessToken = $accessToken;
        $user->refreshToken = $refreshToken;
        $userDao->insert($user);

        // 再取得
        $user = $userDao->getUserByLineId($response->sub);
        $user['new'] = true;
        return $user;

    }
}
