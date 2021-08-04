<?php declare(strict_types=1);

namespace App\Domains\App\Action;

class Update extends CreateUpdateAbstract
{
    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->type = $this->data['type'];
        $this->row->name = $this->data['name'];
        $this->row->payload = $this->data['payload'];
        $this->row->updated_at = date('Y-m-d H:i:s');

        if ($this->row->user_id === $this->auth->id) {
            $this->row->shared = $this->data['shared'];
            $this->row->editable = $this->data['editable'];
        }

        $this->row->save();
    }
}
