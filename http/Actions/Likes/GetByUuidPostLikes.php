<?php

namespace http\Actions\Likes;

use http\Actions\ActionInterface;
use http\ErrorResponse;
use http\Request;
use http\Response;
use http\SuccessfullResponse;
use my\Exceptions\HttpException;
use my\Exceptions\PostLikeNotFoundException;
use my\Model\PostLike;
use my\Model\UUID;
use my\Repositories\PostLikeRepository;

class GetByUuidPostLikes implements ActionInterface
{
    public function __construct(
        private PostLikeRepository $postLikeRepository
    )
    {

    }
    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $postLikes = $this->postLikeRepository->getByPostUuid(new UUID($postUuid));
        } catch (PostLikeNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        $likesData = array_map(function (PostLike $like) {
            return [
                'uuid' => (string)$like->getUuid(),
                'user_uuid' => (string)$like->getUserUuid()
            ];
        }, $postLikes);

        return new SuccessfullResponse([
            'post' => $postUuid,
            'likes' => $likesData
        ]);
    }
}