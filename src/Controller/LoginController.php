<?php
    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpClient\HttpClient;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class LoginController extends AbstractController
    {
        #[Route('/login')]
        public function discordLogin(Request $request): Response
        {
            $baseUrl = $request->getSchemeAndHttpHost();
            $route = $request->getPathInfo();
            $currentUrl = $baseUrl . $route;

            if (!isset($_GET["code"])) {
                $authUrl = $this->buildAuthUrl($currentUrl);
                return $this->redirect($authUrl);
            } else {
                $content = $this->exchangeAuthCode($currentUrl, $_GET["code"]);
                $userInfo = $this->getUserInfo($content["access_token"]);
                $userID = $userInfo["id"];
                return $this->redirect('/user?ID=' . $userID);
            }
        }

        public function buildAuthUrl(string $encodedUrl): string
        {
            $params = [
                'client_id' => $_ENV["DISCORD_CLIENT_ID"],
                'response_type' => 'code',
                'redirect_uri' => $encodedUrl,
                'scope' => 'identify',
            ];
            return $_ENV["DISCORD_API_ENDPOINT"] . '/oauth2/authorize?' . http_build_query($params);
        }

        public function exchangeAuthCode(string $currentUrl, string $code): array
        {
            $client = HttpClient::create();
            $response = $client->request('POST', "{$_ENV['DISCORD_API_ENDPOINT']}/oauth2/token", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $currentUrl,
                ],
                'auth_basic' => "{$_ENV['DISCORD_CLIENT_ID']}:{$_ENV['DISCORD_CLIENT_SECRET']}",
            ]);
            return $response->toArray();
        }

        public function getUserInfo(string $accessToken): array
        {
            $client = HttpClient::create();
            $response = $client->request('GET', "{$_ENV['DISCORD_API_ENDPOINT']}/users/@me", [
                'headers' => [
                    'Authorization' => "Bearer $accessToken",
                ],
            ]);
            return $response->toArray();
        }
    }
