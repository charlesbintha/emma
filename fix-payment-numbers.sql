-- Script pour corriger les numéros de paiement existants
-- Les paiements sont actuellement numérotés 2, 3, 4, 5
-- Ils doivent être numérotés 1, 2, 3, 4

-- Vérifier les numéros actuels avant correction
SELECT
    tontine_subscription_id,
    id,
    payment_number,
    due_date,
    status
FROM payments
ORDER BY tontine_subscription_id, payment_number;

-- Corriger tous les numéros de paiement en soustrayant 1
UPDATE payments
SET payment_number = payment_number - 1
WHERE payment_number > 1;

-- Vérifier après correction
SELECT
    tontine_subscription_id,
    id,
    payment_number,
    due_date,
    status
FROM payments
ORDER BY tontine_subscription_id, payment_number;

-- Vérifier que chaque souscription a bien 4 paiements numérotés 1, 2, 3, 4
SELECT
    tontine_subscription_id,
    COUNT(*) as total_payments,
    MIN(payment_number) as min_payment,
    MAX(payment_number) as max_payment
FROM payments
GROUP BY tontine_subscription_id
HAVING total_payments != 4
    OR min_payment != 1
    OR max_payment != 4;

-- Si la requête ci-dessus retourne des lignes, il y a un problème !
-- Si elle ne retourne rien, tout est bon ✅
