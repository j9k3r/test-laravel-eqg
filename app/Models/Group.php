<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'id_group');
    }

    public function childrenGroups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class, 'id_parent');
    }


    // Связь с дочерними группами
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class, 'id_parent', 'id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_parent');
    }

    public function parents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class, 'id_parent', 'id');
    }

    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    public function totalProductsCount(): int
    {
        $directProductCount = $this->products()->count();

        $descendantProductCount = $this->children()->get()->sum(function ($child) {
            return $child->totalProductsCount();
        });

        return $directProductCount + $descendantProductCount;
    }

    public function getAllChildren(): Collection
    {
        $allChildren = new Collection([$this]);
        $this->appendChildren($allChildren);

        return $allChildren;
    }

    protected function appendChildren(&$collection): void
    {
        foreach ($this->children as $child) {
            $collection->push($child);
            $child->appendChildren($collection);
        }
    }

    public function getAllProducts(): Collection
    {
        $groupIds = $this->getAllChildren()->pluck('id')->toArray();
        return Product::whereIn('id_group', $groupIds)->get();
    }

    public function breadcrumbs(): \Illuminate\Support\Collection
    {
        $breadcrumbs = collect([]);

        $currentGroup = $this;
        while ($currentGroup) {
            $breadcrumbs->prepend($currentGroup);
            $currentGroup = $currentGroup->parent()->first();
        }

        return $breadcrumbs;
    }
}
