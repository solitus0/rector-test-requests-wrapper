# Installation

### Add repository to composer.json
```json
{
  "type": "vcs",
  "url": "git@gitlab.ito.lt:ito-backend/rector-phpunit-withconsecutive.git"
}
```
### Install package
```bash
composer require --dev solitus0/rector-test-requests-wrapper
```

# Rector rules for deprecated withConsecutive method

```php
    $rectorConfig->sets(
        [
            AppSetsList::TEST_REQUESTS_WRAPPER,
        ]
    );
```

# Copy Trait:
```php
<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\Entity\Oauth\AccessToken;

class WebRequestTestCase extends BaseWebTestCase
{
    protected function getAuthorizedRequest(string $url, AccessToken $token): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('GET', $url, [], [], $header);
    }

    protected function getAuthorizedRequestWithQuery(string $url, array $query, AccessToken $token): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('GET', $url, $query, [], $header);
    }

    protected function postAuthorizedRequest(string $url, AccessToken $token): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('POST', $url, [], [], $header);
    }

    protected function patchAuthorizedRequest(string $url, AccessToken $token): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('PATCH', $url, [], [], $header);
    }

    protected function postAuthorizedRequestWithContent(string $url, AccessToken $token, array $content): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('POST', $url, [], [], $header, json_encode($content));
    }

    protected function patchAuthorizedRequestWithContent(string $url, AccessToken $token, array $content): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('PATCH', $url, [], [], $header, json_encode($content));
    }

    protected function requestGet(string $url, array $header = []): void
    {
        $this->client->request('GET', $url, [], [], $header);
    }

    protected function requestPost(string $url, array $header = []): void
    {
        $this->client->request('POST', $url, [], [], $header);
    }

    protected function postWithParameters(string $url, array $parameters = []): void
    {
        $this->client->request('POST', $url, $parameters);
    }

    protected function postWithContent(string $url, array $header = [], array $content = []): void
    {
        $this->client->request('POST', $url, [], [], $header, json_encode($content));
    }

    protected function getAuthorizedRequestWithHeader(string $url, AccessToken $token, array $header): void
    {
        $header = $this->createAuthorizedHeader($token, $header);

        $this->client->request('GET', $url, [], [], $header);
    }

    protected function deleteAuthorizedRequest(string $url, AccessToken $token): void
    {
        $header = $this->createAuthorizedHeader($token);

        $this->client->request('DELETE', $url, [], [], $header);
    }

    protected function requestPostWithFiles(
        string $url,
        ?AccessToken $token = null,
        array $parameters = [],
        array $files = []
    ): void {
        $header = [];
        if ($token) {
            $header = $this->createAuthorizedHeader($token);
        }

        $this->client->request('POST', $url, $parameters, $files, $header);
    }

    private function createAuthorizedHeader(AccessToken $token, array $header = []): array
    {
        $defaultHeader = [
            'HTTP_Authorization' => 'Bearer ' . $token->getToken(),
            'CONTENT_TYPE' => 'application/json',
        ];

        foreach ($header as $key => $value) {
            $defaultHeader[$key] = $value;
        }

        return $defaultHeader;
    }
}

```