<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class for check right of an user for a customer
 * @author Tristan
 * @version 1
 */
class CustomerVoter extends Voter
{
    const CUST_ACCESS = 'CUST_ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CUST_ACCESS'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }
        /** @var Customer $customer */
        $customer = $user;
        
        if (!$subject instanceof User) {
            return false;
        }

        switch ($attribute) {
            case 'CUST_ACCESS':
                return $this->canAccess($customer, $subject);
        }

        return false;
    }

    /**
     * Check if connected customer own this user
     * @param Customer $customer logged Customer
     * @param User $user concerned user
     * @return bool response
     */
    private function canAccess(Customer $customer, User $user) : bool
    {
        return $customer === $user->getCustomer();
    }
}
