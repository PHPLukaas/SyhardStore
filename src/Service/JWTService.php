<?php

namespace App\Service;

use DateTimeImmutable;


/**
 * Service pour la gestion des JWT (JSON Web Tokens).
 */
class JWTService
{
    /**
     * Génère un token JWT.
     *
     * @param array $header Les données du header du token.
     * @param array $payload Les données du payload du token.
     * @param string $secret Le secret utilisé pour la génération de la signature.
     * @param int $validity La durée de validité du token en secondes.
     * @return string Le token JWT généré.
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0)
        {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }



        // on encode en base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // on "nettoie" les valeurs encodées (retrait des +, / et =)
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // on génére la signature
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // on cree le token
        $jwt = "$base64Header.$base64Payload.$base64Signature";


        return $jwt;
    }

    /**
     * Vérifie si un token est valide (correctement formé).
     *
     * @param string $token Le token à vérifier.
     * @return bool True si le token est valide, sinon false.
     */
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    /**
     * Récupère le payload d'un token.
     *
     * @param string $token Le token à partir duquel extraire le payload.
     * @return array Le tableau associatif représentant le payload.
     */
    public function getPayload(string $token): array
    {
        //on démonte le token
        $array = explode('.', $token);

        // on decode le payload
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }

    /**
     * Récupère le header d'un token.
     *
     * @param string $token Le token à partir duquel extraire le header.
     * @return array Le tableau associatif représentant le header.
     */
    public function getHeader(string $token): array
    {
        //on démonte le token
        $array = explode('.', $token);

        // on decode le header
        $header = json_decode(base64_decode($array[0]), true);
        return $header;
    }

    /**
     * Vérifie si un token a expiré.
     *
     * @param string $token Le token à vérifier.
     * @return bool True si le token a expiré, sinon false.
     */
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTimeImmutable();
        return $payload['exp'] < $now->getTimestamp();
    }

    /**
     * Vérifie la signature d'un token.
     *
     * @param string $token Le token à vérifier.
     * @param string $secret Le secret utilisé pour la génération de la signature.
     * @return bool True si la signature du token est valide, sinon false.
     */
    public function check(string $token, string $secret)
    {
        // on recupere le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // on régénère un token
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }

}