<?php

namespace App\Http\Resources\User;

use App\Domain\User\Entity\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Список игроков.
 * @OA\Schema(
 *     title="Игроки",
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/UserResource")
 *     )
 * )
 * @package App\Http\Resources\User
 */
class UserListResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request = null): array
    {
        $items = [];

        foreach ($this->getUsers() as $user) {
            $items[] = (new UserResource($user))->toArray();
        }

        return $items;
    }

    /**
     * Возвращает список игроков.
     *
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->resource;
    }
}
