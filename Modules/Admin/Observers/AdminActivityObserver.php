<?php
namespace Modules\Admin\Observers;

use Modules\Admin\Entities\AdminActivity;

class AdminActivityObserver
{
    /**
     * Listen to the created event.
     *
     * @param $model
     * @return void
     */
    public function created($model)
    {
        $newData = $model->toArray();
        $oldData = [];

        $event = "created";
        $activity = "Created a new record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /*
    public function saved($model)
    {
        $primaryKey = $model->getKeyName();
        $id = $model->getOriginal($primaryKey);

        if(isset($id) && $id != "")
        {
            //this is a record update
            $newData = $model->getChanges();

            $oldData = [];
            foreach($newData as $key => $value)
            {
                $oldData[$key] = $model->getOriginal($key);
            }

            $event = "updated";
            $activity = "Updated a record.";
        }
        else
        {
            //this is a new record
            $newData = $model->toArray();
            $oldData = [];

            $event = "created";
            $activity = "Created a new record.";
        }

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }*/

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function updated($model)
    {
        //this is a record update
        $newData = $model->getChanges();

        $oldData = [];
        foreach($newData as $key => $value)
        {
            $oldData[$key] = $model->getOriginal($key);
        }

        $event = "updated";
        $activity = "Updated a record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function deleted($model)
    {
        //this is a record update
        $newData = [];
        $oldData = $model->toArray();

        //determine if this is a soft delete or force delete
        if (method_exists($model, "forceDelete"))
        {
            $event = "trashed";
            $activity = "Moved a record to the trash.";
        }
        else
        {
            $event = "deleted";
            $activity = "Permanently deleted a record.";
        }

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function restored($model)
    {
        //this is a record update
        $newData = [];
        $oldData = $model->toArray();

        $event = "restored";
        $activity = "Deleted a record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * Listen to the deleting event.
     *
     * @param $model
     * @return void
     */
    public function forceDeleted($model)
    {
        //this is a record update
        $newData = [];
        $oldData = $model->toArray();

        $event = "deleted";
        $activity = "Permanently deleted a record.";

        $this->recordActivity($model, $event, $oldData, $newData, $activity);
    }

    /**
     * @param $model
     * @param string $event
     * @param array $oldData
     * @param array $newData
     * @param string $activity
     */
    private function recordActivity($model, $event, $oldData=[], $newData=[], $activity="")
    {
        $primaryKey = $model->getKeyName();
        $modelName = $className = get_class($model);
        $modelId = $model->$primaryKey;

        $request = request();

        $admin_login_history_id = $request->session()->get("admin_login_history_id");
        $admin_id = auth("admin")->user()->admin_id;

        $adminActModel = new AdminActivity();
        $adminActModel->admin_login_history_id = $admin_login_history_id;
        $adminActModel->admin_id = $admin_id;
        $adminActModel->activity = $activity;
        $adminActModel->event = $event;
        $adminActModel->activity_old_data = json_encode($oldData);
        $adminActModel->activity_new_data = json_encode($newData);
        $adminActModel->activity_model_name = $modelName;
        $adminActModel->activity_model = $modelId;
        $adminActModel->activity_at = date("Y-m-d H:i:s", time());

        $adminActModel->save();
    }
}
