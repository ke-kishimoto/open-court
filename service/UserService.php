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
        $result = $lineApi->tokenVerify($idToken);

        // プロフィール取得
        // $response = $lineApi->getLineProfile($accessToken);
        // $response = $lineApi->getLineProfileByCode($code);

        // DBにそのLINE IDのユーザーがいるかどうか確認
        $userDao = new UsersDao();
        $user = $userDao->getUserByLineId($result->sub);

        if($user) {
            // 存在する場合
            if(empty($user['name'])) {
                $user['name'] = $result->name;
                $user['admin_flg'] = 0;
                $user['line_id'] = $result->sub;
                $user['access_token'] = $accessToken;
                $user['refresh_token'] = $refreshToken;
                $user['remark'] = '';
                $userDao->update($user);
            } else {
                return $user;
            }
        } else {
            // 存在しない場合
            $user = [];
            $user['name'] = $result->name;
            $user['admin_flg'] = 0;
            $user['line_id'] = $result->sub;
            $user['access_token'] = $accessToken;
            $user['refresh_token'] = $refreshToken;
            $user['remark'] = '';
            $userDao->insert($user);
        }

        // 再取得
        $user = $userDao->getUserByLineId($result->sub);
        return $user;

    }
}
