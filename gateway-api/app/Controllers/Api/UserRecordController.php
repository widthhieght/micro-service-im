<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:54
 */

namespace App\Controllers\Api;
use ServiceComponents\Common\Message;
use ServiceComponents\Rpc\User\RecordServiceInterface;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\ValidatorFrom;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Class UserRecordControoler
 * @package App\Controllers\Api
 * @Controller(prefix="/api/im")
 */
class UserRecordController extends BaseController
{
    /**
     * @Reference("userService")
     * @var RecordServiceInterface
     */
    private $userRecordService;
    /**
     * @return mixed
     * @RequestMapping(route="record",method={RequestMethod::GET})
     * @Strings(from=ValidatorFrom::GET,name="id")
     * @Strings(from=ValidatorFrom::GET,name="type")
     * @Strings(from=ValidatorFrom::GET,name="token")
     */
    public function getChatRecordByToken()
    {
        $this->getCurrentUser();
        $res = $this->userRecordService->getAllChatRecordById($this->user['id'] , \request()->query());
        return Message::success($res);
    }

    /**
     * 更新已读消息
     * @RequestMapping(route="chat/record/read",method={RequestMethod::POST})
     * @Strings(from=ValidatorFrom::POST,name="uid")
     * @Strings(from=ValidatorFrom::POST,name="type")
     * @Strings(from=ValidatorFrom::POST,name="token")
     * @param Request $request
     */
    public function updateIsReadChatRecord($request)
    {
        $this->getCurrentUser();
        $where = ['to_id' => $this->user['id'],'uid' => $request->post('uid'),'is_read' => 0];
        $data = ['is_read' => 1];
        $type = $request->post('type');
        $this->userRecordService->updateChatRecordIsRead($where,$data,$type);
        return Message::success([],'收取消息成功');
    }
}