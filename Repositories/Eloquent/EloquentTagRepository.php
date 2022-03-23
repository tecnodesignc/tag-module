<?php

namespace Modules\Tag\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Tag\Events\TagIsCreating;
use Modules\Tag\Events\TagIsUpdating;
use Modules\Tag\Events\TagWasCreated;
use Modules\Tag\Events\TagWasUpdated;
use Modules\Tag\Repositories\TagRepository;

class EloquentTagRepository extends EloquentBaseRepository implements TagRepository
{
    /**
     * Get all the tags in the given namespace
     * @param string $namespace
     * @return Collection
     */
    public function allForNamespace($namespace): Collection
    {
        return $this->model->with('translations')->where('namespace', $namespace)->get();
    }
    /**
     * Create a resource
     * @param  $data
     * @return Model|Collection|Builder|array|null
     */
    public function create($data): Model|Collection|Builder|array|null
    {
        event($event = new TagIsCreating($data));
        $tag = $this->model->create($event->getAttributes());

        event(new TagWasCreated($tag));

        return $tag;
    }
    /**
     * Update a resource
     * @param  $tag
     * @param array $data
     * @return Model|Collection|Builder|array|null
     */
    public function update($tag, $data): Model|Collection|Builder|array|null
    {
        event($event = new TagIsUpdating($tag, $data));
        $tag->update($event->getAttributes());

        event(new TagWasUpdated($tag));

        return $tag;
    }
}
