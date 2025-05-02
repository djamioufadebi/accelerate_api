<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client' => new ClientResource($this->client),
            'invoice_number' => $this->invoice_number,
            'total_ht' => $this->total_ht,
            'issue_date' => $this->issue_date->toDateString(),
            'due_date' => $this->due_date->toDateString(),
            'lines' => $this->lines->map(fn($line) => 
                [
                    'description' => $line->description,
                    'amount' => $line->amount,
                ]),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
