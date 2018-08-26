<?php

namespace CMS\Controllers\Savas;

use CMS\Components\Controller;
use savas\Models\Savas\Application\Application;
use savas\Models\Savas\Application\Member;

class ApplicationController extends Controller
{

    public function preDispatch ()
    {
        if (!self::auth()->loggedIn())
        {
            $this->app()->respond(self::json()->failure(['message' => 'Not logged in']));
            die;
        }
    }

    public function listAction ()
    {
        $userID = self::auth()->userID();
        $rows   = self::db()->from('s_application a')
            ->leftJoin('s_application_member am ON am.appID = a.id')
            ->where('am.userID', $userID)
            ->select(null)->select('a.*')
            ->fetchAll();

        return self::json()->success([
            'data' => $rows
        ]);
    }

    public function saveAction ()
    {
        $input = self::request()->getParams();
        $id    = (int) self::request()->getParam('id');
        $isNew = $id <= 0;

        $repository = Application::repository();

        if (!$isNew)
        {
            $model = $repository->find($id);

            if (!($model instanceof Application))
            {
                return self::json()->failure(['message' => 'Application by id not found.']);
            }

            if (!Application::isMember($model->id))
            {
                return self::json()->failure(['message' => 'You are not permitted to edit this application.']);
            }
        }
        else
        {
            /** @var Application $model */
            $model = $repository->create();
            $model->created    = date('Y-m-d H:i:s');
            $model->changed    = date('Y-m-d H:i:s');
            $model->publicKey  = md5(uniqid('publicKey'));
            $model->privateKey = md5(uniqid('privateKey'));
        }

        $model->set('label', $input['label']);
        $model->set('description', $input['description']);

        /** @var \Savas\Components\ModelValidator $validator */
        $validator = self::modelValidator();

        if ($validator->validate($model))
        {
            $model->save();

            if ($isNew)
            {
                $member = Member::create();
                $member->appID  = $model->id;
                $member->userID = self::auth()->userID();
                $member->save();
            }

            return self::json()->success([
                'data' => $model->toArray(false)
            ]);
        }

        return self::json()->failure([
            'messages' => $validator->getMessages()
        ]);
    }

    public function detailAction()
    {
        $id         = (int) self::request()->getParam('id');
        $repository = Application::repository();

        if ($id > 0)
        {
            $model = $repository->find($id);

            if (!($model instanceof Application))
            {
                return self::json()->failure(['message' => 'Application by id not found.']);
            }

            if (!Application::isMember($model->id))
            {
                return self::json()->failure(['message' => 'You are not permitted to edit this application.']);
            }

            return self::json()->success([
                'data' => $model->toArray(false)
            ]);
        }
        else
        {
            return self::json()->failure(['message' => 'Missing required param: id']);
        }
    }

    public function removeAction ()
    {
        $id         = (int) self::request()->getParam('id');
        $repository = Application::repository();

        if ($id > 0)
        {
            $model = $repository->find($id);

            if (!($model instanceof Application))
            {
                return self::json()->failure(['message' => 'Application by id not found.']);
            }

            if (!Application::isMember($model->id))
            {
                return self::json()->failure(['message' => 'You are not permitted to edit this application.']);
            }

            $model->remove();

            return self::json()->success();
        }
        else
        {
            return self::json()->failure(['message' => 'Missing required param: id']);
        }
    }

}