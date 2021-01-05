<?php

namespace SmartContact\SmartLogClient\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use ReflectionClass;
use SmartContact\SmartLogClient\SmartLogClient;

trait TrackingApplicationLogs
{
    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->registerApplicationLog($event);
            });
        }
    }

    /**
     * Fetch the changes to the model.
     *
     * @return array|null
     */
    public function applicationLogsChanges(): ?array
    {
        $original = $this->getOriginal();

        foreach ($original as $key => $value) {
            if (is_array($value) or is_object($value)) {
                $original[$key] = json_encode($value);
            }
        }

        $attributes = $this->getAttributes();

        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($original, $attributes), ['created_at', 'updated_at']),
                'after' => Arr::except($this->getChanges(), ['created_at', 'updated_at']),
            ];
        }

        return [];
    }

    /**
     * @param null $event
     * @throws \ReflectionException
     */
    protected function registerApplicationLog($event = null)
    {
        SmartLogClient::info([
            'is_application_log' => 1,
            'user' => auth()->user() ? auth()->user()->email : null,
            'description' => $this->getApplicationLogDescription($this, $event),
            'log' => $this->applicationLogsChanges(),
            'extra' => [
                'resource' => self::class,
                'resourceId' => $this->{$this->getKeyName()},
                'input' => request()->except($this->hidden)
            ],
        ]);
    }

    /**
     * @param $model
     * @param $action
     * @return string
     * @throws \ReflectionException
     */
    protected function getApplicationLogDescription($model, $action): string
    {
        $description = strtolower((new ReflectionClass($model))->getShortName());

        return "{$action}_{$description}";
    }

    /**
     * @return string[]
     */
    protected static function getModelEvents(): array
    {
        $hasSoftDeletes = in_array(
            SoftDeletes::class,
            (new ReflectionClass(self::class))->getTraitNames()
        );

        $defaultsEvents = ['created', 'updated', 'deleted'];

        if ($hasSoftDeletes) {
            $defaultsEvents[] = 'restored';
        }

        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return $defaultsEvents;
    }
}
