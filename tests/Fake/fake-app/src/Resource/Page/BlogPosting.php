<?php

namespace FakeVendor\HelloWorld\Resource\Page;

use BEAR\QueryRepository\Header;
use BEAR\RepositoryModule\Annotation\CacheableResponse;
use BEAR\Resource\Annotation\Embed;
use BEAR\Resource\Code;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\CacheControl;
use Koriym\HttpConstants\ResponseHeader;

#[CacheableResponse]
class BlogPosting extends ResourceObject
{
    public function onGet(int $id = 0): self
    {
        $this->headers[Header::SURROGATE_KEY] = 'blog-posting-page';
        $this->body = ['title' => 'Hello BEAR.Sunday!'];

        return $this;
    }

    public function onDelete(int $id = 0): self
    {
        if ($id !== 0) {
            $this->code = Code::BAD_REQUEST;

            return $this;
        }

        return $this;
    }
}
