<?php
namespace Backend\Controllers\Sys;

use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Controllers\BaseController;
use Backend\Models\Sys\Manage;

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
    public function editAction() : ?string
    {
        $data = $this->request->getPostedData('post');
        if (!$data['id'] || !$data['name'] || !$data['desc'] || !$data['domain'] || $data['status'] == '') {
            throw new ApiException(ErrorCode::POST_DATA_NOT_PROVIDED);
        }
        $jwt               = $this->request->getToken();
        $session           = $this->jwt->getSession($jwt);
        $data['create_by'] = $session->getIdentity();
        $data['create_at'] = time();
        $data['update_at'] = time();
        $data['update_by'] = $session->getIdentity();
        $model             = new Manage();
        try {
            $ret = $model->edit($data, $data['id']);
            if (!$ret) {
                throw new \Exception('系统错误，数据添加失败～');
            }
            return [
                'id'        => $ret,
                'msg'       => '添加成功',
                'timestamp' => time()
            ];
        } catch (\Exception $ex) {
            throw  new ApiException(ErrorCode::POST_DATA_INVALID);
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
    public function addAction() : ?string
    {
        $data = $this->request->getPostedData('post');
        if (!$data['id'] || !$data['name'] || !$data['desc'] || !$data['domain'] || $data['status'] == '') {
            throw new ApiException(ErrorCode::POST_DATA_NOT_PROVIDED);
        }
        $jwt               = $this->request->getToken();
        $session           = $this->jwt->getSession($jwt);
        $data['create_by'] = $session->getIdentity();
        $data['create_at'] = time();
        $data['update_at'] = time();
        $data['update_by'] = $session->getIdentity();
        $model             = new Manage();
        try {
            $ret = $model->add($data);
            if (!$ret) {
                throw new \Exception('系统错误，数据添加失败～');
            }
            return [
                'id'        => $ret,
                'msg'       => '添加成功',
                'timestamp' => time()
            ];
        } catch (\Exception $ex) {
            throw  new ApiException(ErrorCode::POST_DATA_INVALID);
        }
    }

}