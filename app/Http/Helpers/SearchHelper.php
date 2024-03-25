<?php


namespace App\Http\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait SearchHelper
{
    private null|string $searchTerm;
    private bool $withTrashed;
    private bool $onlyTrashed;
    private Builder|Model $searchQuery;
    private ?int $page;
    private mixed $response;
    private array $params;
    private array $relationshipParams;
    private string $sortBy;
    private string $sortOrder;

    public function buildSearchParams(
        ?int $page = null,
        null|string $searchTerm = null,
        bool $withTrashed = false,
        bool $onlyTrashed = false,
        array $params = [],
        array $relationshipParams = [],
        string $sortBy = 'created_at',
        string $sortOrder = 'desc'
    ): void
    {
        $this->searchTerm = $searchTerm;
        $this->withTrashed = $withTrashed;
        $this->onlyTrashed = $onlyTrashed;
        $this->page = $page;
        $this->params = $params;
        $this->relationshipParams = $relationshipParams;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
    }

    private function getWithTrashed(): void
    {
        if ($this->withTrashed) $this->searchQuery->withTrashed();
    }

    private function getOnlyTrashed(): void
    {
        if ($this->onlyTrashed) $this->searchQuery->onlyTrashed();
    }

    private function buildPaginationResponse(): void
    {
        if(is_null($this->page)) return;
        $this->response = $this
            ->searchQuery
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(10, ['*'], 'page', $this->page);
    }


    private function buildAllPossibleResponse(): void
    {
        if(!is_null($this->page)) return;
        $this->response = $this
            ->searchQuery
            ->orderBy($this->sortBy, $this->sortOrder)
            ->get()
            ->toArray();
    }

    public function getResponse()
    {
        if(is_null($this->page)) return $this->response;
        $nextPage = $this->response->nextPageUrl();
        $previousPage = $this->response->previousPageUrl();
        $paramsToUrlParams = http_build_query($this->params);
        $urlParams = '&'.$paramsToUrlParams.'&searchTerm='.$this->searchTerm.'&withTrashed='.$this->withTrashed.'&onlyTrashed='.$this->onlyTrashed;
        return [
            'item' => $this->response->toArray()['data'],
            'meta_data' => [
                'total' => $this->response->total(),
                'per_page' => $this->response->perPage(),
                'current_page' => $this->response->currentPage(),
                'next_page_url' => $nextPage. (!is_null($nextPage) ? $urlParams: ''),
                'previous_page_url' => $previousPage. (!is_null($previousPage) ? $urlParams: ''),
            ]
        ];
    }
}
