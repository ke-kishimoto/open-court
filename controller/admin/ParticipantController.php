<?php 
namespace controller\admin;
use controller\BaseController;
use dao\DetailDao;
use dao\CompanionDao;
use dao\UsersDao;
use entity\Participant;
use service\EventService;

class ParticipantController extends BaseController
{

    public function participantNameList() {
        parent::adminHeader();

        $detailDao = new DetailDao();
        $participantList = $detailDao->getParticipantList($_GET['gameid'], $_GET['occupation'], $_GET['sex'], $_GET['waiting_flg']);

        $gameId = $_GET['gameid'];
        $title = '参加者名一覧';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/participantNameList.php');
        include('./view/admin/common/footer.php');
    }

    public function participantInfo() {
        parent::adminHeader();

        $detailDao = new DetailDao();
        $userListClass = '';
        if(isset($_GET['id'])) {
            $participant = $detailDao->getParticipant($_GET['id']);
            $companionDao = new CompanionDao();
            $companionList = $companionDao->getCompanionList($participant['id']);
            $userListClass = 'hidden';
        } else {
            $participant['id'] = '';
            $participant['name'] = '';
            $participant['email'] = '';
            $participant['occupation'] = 1;
            $participant['occupation_name'] = '社会人';
            $participant['sex'] = 1;
            $participant['sex_name'] = '男性';
            $participant['companion'] = 0;
            $participant['remark'] = '';
            $participant['line_id'] = '';

            $companionList = array();

            $userDao = new UsersDao();
            $userList = $userDao->getUserList();
        }


        // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
        $toke_byte = openssl_random_pseudo_bytes(16);
        $csrf_token = bin2hex($toke_byte);
        // 生成したトークンをセッションに保存します
        $_SESSION['csrf_token'] = $csrf_token;

        $title = '参加者情報登録';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        include('./view/admin/participantInfo.php');
        include('./view/admin/common/footer.php');
    }

    // 参加者登録
    public function participantRegist() 
    {
        parent::adminHeader();

        if (isset($_POST["csrf_token"]) 
        && $_POST["csrf_token"] === $_SESSION['csrf_token']) {

            if (isset($_POST['register'])) {
                $service = new EventService();
                $participant = new Participant();
                $participant->gameId = (int)$_POST['game_id'];
                $participant->occupation = (int)$_POST['occupation'];
                $participant->sex = (int)$_POST['sex'];
                $participant->name = $_POST['name'];
                $participant->email = $_POST['email'];
                $participant->remark = $_POST['remark'];

                $companions = [];
                if($_POST['companion'] > 0) {
                    for($i = 1; $i <= $_POST['companion']; $i++) {
                        // $companion = new Companion();
                        // $companion->occupation =  $_POST['occupation-' . $i];
                        // $companion->sex = $_POST['sex-' . $i];
                        // $companion->name =  $_POST['name-' . $i];
                        $companion = [];
                        $companion['occupation'] =  $_POST['occupation-' . $i];
                        $companion['sex'] = $_POST['sex-' . $i];
                        $companion['name'] =  $_POST['name-' . $i];
                        $companions[] = $companion;
                    }
                }
                if($_POST['id'] !== '') {
                    $participant->id = $_POST['id'];
                    $service->participantUpdate($participant, $companions);
                } else {
                    $service->oneParticipantRegist($participant, $companions, EventService::MODE_ADMIN);
                }
            } else {
                $detailDao = new DetailDao();
                $detailDao->updateDeleteFlg($_POST['id']);
            }
            unset($_SESSION['csrf_token']);
        } else {
            header('Location: ./index.php');
        }

        $title = '参加者登録完了';
        include('./view/admin/common/head.php');
        include('./view/admin/common/header.php');
        $msg = '参加者の登録が完了しました。';
        include('./view/admin/complete.php');
        include('./view/admin/common/footer.php');
    }

}
