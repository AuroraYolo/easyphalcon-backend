<?php
namespace Backend\Components\Auth\Jwt;

use Backend\Component\Auth\Session;
use Backend\Components\Core\App;
use Backend\Constant\Services;
use Firebase\JWT\JWT as JwtCore;

class Jwt
{
    const ALGORITHM_HS256 = 'HS256';
    const ALGORITHM_HS512 = 'HS512';
    const ALGORITHM_HS384 = 'HS384';
    const ALGORITHM_RS256 = 'RS256';

    protected $algorithm;
    private $keyCacheExpire = 30 * 24 * 60 * 60;

    /**
     * Jwt constructor.
     *
     * @param string $algorithm
     *
     * @throws \Exception
     */
    public function __construct($algorithm = self::ALGORITHM_HS256)
    {
        if (!class_exists('\Firebase\JWT\JWT')) {
            throw new \Exception(ErrorCode::GENERAL_SYSTEM, 'JWT class is needed for the JWT token parser');
        }
        $this->algorithm = $algorithm;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param Session $session
     * @param null    $expirationTime
     *
     * @return string
     * @throws \Exception
     */
    public function getToken(Session $session, $expirationTime = null)
    {
        $tokenData = $this->create(Services::CRYPT_KEY, $session->getIdentity(), $session->getStartTime(),
            $session->getExpirationTime());

        return $this->encode($tokenData);
    }

    /**
     * @param $token
     *
     * @return Session
     * @throws \Exception
     */
    public function getSession($token)
    {
        $tokenData = $this->decode($token);
        return new Session($tokenData->sub, $tokenData->iat, $tokenData->exp, $token);
    }

    protected function create($issuer, $user, $iat, $exp)
    {
        return [

            /*
            The iss (issuer) claim identifies the principal
            that issued the JWT. The processing of this claim
            is generally application specific.
            The iss value is a case-sensitive string containing
            a StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "iss" => $issuer,

            /*
            The sub (subject) claim identifies the principal
            that is the subject of the JWT. The Claims in a
            JWT are normally statements about the subject.
            The subject value MUST either be scoped to be
            locally unique in the context of the issuer or
            be globally unique. The processing of this claim
            is generally application specific. The sub value
            is a case-sensitive string containing a
            StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "sub" => $user,

            /*
            The iat (issued at) claim identifies the time at
            which the JWT was issued. This claim can be used
            to determine the age of the JWT. Its value MUST
            be a number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "iat" => $iat,

            /*
            The exp (expiration time) claim identifies the
            expiration time on or after which the JWT MUST NOT
            be accepted for processing. The processing of the
            exp claim requires that the current date/time MUST
            be before the expiration date/time listed in the
            exp claim. Implementers MAY provide for some small
            leeway, usually no more than a few minutes,
            to account for clock skew. Its value MUST be a
            number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "exp" => $exp,
        ];
    }

    /**
     * @param $token
     *
     * @return string
     * @throws \Exception
     */
    public function encode($token)
    {
        return JwtCore::encode($token, $this->getPrivateKey(), $this->algorithm);
    }

    /**
     * @param $token
     *
     * @return object
     * @throws \Exception
     */
    public function decode($token)
    {
        return JwtCore::decode($token, $this->getPublicKey(), [$this->algorithm]);
    }

    public function getSessionWithRedis($token)
    {
        // TODO: Implement getSessionWithRedis() method.
    }

    /**
     * 获取密钥
     * @return bool|resource
     * @throws \Exception
     */
    private function getPrivateKey()
    {
        $path = App::globalConfig()->jwt->private_key;
        $mkey = md5(__METHOD__ . ENVIRONMENT . $path);
        try {
            $key = App::redis()->get($mkey);
            if (empty($key)) {
                $key = file_get_contents($path);
                App::redis()->save($mkey, $key, $this->keyCacheExpire);
            }
        } catch (\Exception $ex) {
            App::Logger()->error($ex->getMessage());
            $key = file_get_contents($path);
        }
        return openssl_get_privatekey($key);
    }

    /**
     * 获取公钥
     * @return resource
     * @throws \Exception
     */
    private function getPublicKey()
    {
        $path = App::globalConfig()->jwt->public_key;
        $mkey = md5(__METHOD__ . ENVIRONMENT . $path);
        try {
            $key = App::redis()->get($mkey);
            if (empty($key)) {
                $key = file_get_contents($path);
                App::redis()->save($mkey, $key, $this->keyCacheExpire);
            }
        } catch (\Exception $ex) {
            App::Logger()->error($ex->getMessage());
            $key = file_get_contents($path);
        }
        return openssl_pkey_get_public($key);
    }
}