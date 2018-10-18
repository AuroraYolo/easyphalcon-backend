<?php
namespace Backend\Controllers\Sys;

use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Components\Validation\ValidateType;
use Backend\Controllers\BaseController;
use Backend\Models\Sys\Manage;
use Phalcon\Validation;

/**
 * Class SysManageController
 * @package Backend\Controllers\Sys
 * @RoutePrefix('/sys/sysManage')
 */
class SysManageController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * @Post(
     *     '/index'
     *     )
     */
    public function indexAction()
    {
    }

    /**
     * @Route(
     *     '/edit',
     *     methods = {'POST'}
     * )
     * @throws \Exception
     * @return array|string
     */
    public function editAction() : ?array
    {
        $data     = $this->request->getPostedData('post');
        $messages = $this->validation->validate($data, [
            ['id', ValidateType::PRESENCE_OF, 'ID不能为空'],
            ['ms_name', ValidateType::PRESENCE_OF, '系统名称不能为空'],
            ['ms_desc', ValidateType::PRESENCE_OF, '描述不能为空'],
            ['ms_domain', ValidateType::PRESENCE_OF, '系统域名不能为空'],
            ['status', ValidateType::PRESENCE_OF, '状态不能为空'],
        ]);
        if (count($messages)) {
            foreach ($messages as $message) {
                throw new ApiException(ErrorCode::POST_DATA_INVALID, $message);
            }
        }
        $jwt               = $this->request->getToken();
        $session           = $this->jwt->getSession($jwt);
        $data['update_at'] = time();
        $data['update_by'] = $session->getIdentity();
        try {
            $model = new Manage();
            $ret   = $model::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $data['id']
                    ]
                ]
            );
            if (!$ret) {
                throw new ApiException(ErrorCode::POST_DATA_INVALID, '该条信息不存在~');
            }
            $ret = $model->edit($data, $data['id']);
            if (!$ret) {
                throw new \Exception('系统错误，数据添加失败～');
            }
            return [
                'id'        => $ret,
                'msg'       => '修改成功',
                'timestamp' => time()
            ];
        } catch (\Exception $ex) {
            throw  new ApiException(ErrorCode::POST_DATA_INVALID, $ex->getMessage());
        }
    }

    /**
     * @Route(
     *     '/add',
     *     methods = {'POST'}
     * )
     * @throws \Exception
     * @return array|string
     */
    public function addAction() : ?array
    {
        $data     = $this->request->getPostedData('post');
        $messages = $this->validation->validate($data, [
            ['ms_name', ValidateType::PRESENCE_OF, '系统名称不能为空'],
            ['ms_desc', ValidateType::PRESENCE_OF, '描述不能为空'],
            ['ms_domain', ValidateType::PRESENCE_OF, '系统域名不能为空'],
            ['status', ValidateType::PRESENCE_OF, '状态不能为空'],
        ]);
        if (count($messages)) {
            foreach ($messages as $message) {
                throw new ApiException(ErrorCode::POST_DATA_INVALID, $message);
            }
        }
        $jwt               = $this->request->getToken();
        $session           = $this->jwt->getSession($jwt);
        $data['create_by'] = $session->getIdentity();
        $data['create_at'] = time();
        $data['update_at'] = time();
        $data['update_by'] = $session->getIdentity();
        try {
            $model = new Manage();
            $ret   = $model->add($data);
            if (!$ret) {
                throw new \Exception('系统错误，数据添加失败～');
            }
            return [
                'id'        => $ret,
                'msg'       => '添加成功',
                'timestamp' => time()
            ];
        } catch (\Exception $ex) {
            throw  new ApiException(ErrorCode::POST_DATA_INVALID, $ex->getMessage() ?? null);
        }
    }

}