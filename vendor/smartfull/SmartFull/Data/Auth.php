<?php

namespace SmartFull\Data;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use SmartFull\Utils\Strings;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use SmartFull\Data\Traits\ArrayTools;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Auth
{
    use ArrayTools;

    private function __construct()
    {
    }

    public static function createToken (array $credential) : string {
        $signer = new Sha256();

        $bearer = self::arrayToObject($credential);

        $token = (new Builder())->setIssuer($bearer->iss)       // Configures the issuer (iss claim)
                                ->setAudience($bearer->aud)     // Configures the audience (aud claim)
                                ->setId('4f1g23a12aa', true)    // Configures the id (jti claim), replicating as a header item
                                ->setIssuedAt(time())              // Configures the time that the token was issue (iat claim)
                                ->setNotBefore(time() + 60)             // Configures the time that the token can be used (nbf claim)
                                ->setExpiration(time() + 3600)            // Configures the expiration time of the token (nbf claim)
                                ->set('usr', $bearer->usr)      // Configures a new claim, called "uid"
                                ->set('uid', $bearer->uid)      // Configures a new claim, called "uid"
                                ->sign($signer,$bearer->pwd)
                                ->getToken();                   // Retrieves the generated token

        return $token;

    }

    public static function validaToken (string $token, Request $request) : boolean {
        $token = (new Parser())->parse((string) $token); // Parses from a string

        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer('http://example.com');
        $data->setAudience('http://example.org');
        $data->setId('4f1g23a12aa');

        var_dump($token->validate($data)); // false, because we created a token that cannot be used before of `time() + 60`

        $data->setCurrentTime(time() + 60); // changing the validation time to future

        var_dump($token->validate($data)); // true, because validation information is equals to data contained on the token

        $data->setCurrentTime(time() + 4000); // changing the validation time to future

        var_dump($token->validate($data)); // false, because token is expired since current time is greater than exp

    }

    public static function validateStructure (Strings $Authorization) : boolean {

    }

}