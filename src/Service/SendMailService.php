<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Service pour l'envoi de courriers électroniques.
 */
class SendMailService
{
    private $mailer;

    /**
     * Constructeur de la classe SendMailService.
     *
     * @param MailerInterface $mailer L'objet de gestion de l'envoi des courriers électroniques.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un courrier électronique.
     *
     * @param string $from L'adresse email de l'expéditeur.
     * @param string $to L'adresse email du destinataire.
     * @param string $subject Le sujet du courrier électronique.
     * @param string $template Le nom du template Twig à utiliser pour le contenu HTML du courrier électronique.
     * @param array $context Le contexte contenant les variables à utiliser dans le template Twig.
     * @return void
     */
    public function send(string $from, string $to, string $subject, string $template, array $context) : void
    {
        /**
         * Création d'un email
         */

        // Création d'un email
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // Envoi de l'email
        $this->mailer->send($email);
    }

}