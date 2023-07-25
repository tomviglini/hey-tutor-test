<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;

class SqlServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Builder::macro('joinLateral', function ($query, $as, $type = 'inner') {
            [$query, $bindings] = $this->createSub($query);
            $expression = 'lateral ('.$query.') as '.$this->grammar->wrapTable($as).' on true';
            $join = $this->newJoinClause($this, $type, new Expression($expression));
            $this->joins[] = $join;
            $this->addBinding($bindings, 'join');
            return $this;
        });
    }
}
