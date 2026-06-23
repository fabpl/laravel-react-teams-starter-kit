import { Link } from '@inertiajs/react';
import { CheckCircle2, CreditCard, Sparkles } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTranslation } from '@/lib/i18n';
import type { BillingPlan, CurrentSubscription } from '@/types';

function formatPrice(cents: number): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
    }).format(cents / 100);
}

type Props = {
    plans: BillingPlan[];
    currentSubscription?: CurrentSubscription | null;
    getCheckoutUrl: (priceId: string) => string;
    getPortalUrl?: () => string;
};

export default function PlanList({
    plans,
    currentSubscription,
    getCheckoutUrl,
    getPortalUrl,
}: Props) {
    const { t } = useTranslation();

    const statusLabel = (subscription: CurrentSubscription) => {
        if (subscription.cancelled && !subscription.onGracePeriod) {
            return t('billing.subscriptions.cancelled');
        }

        if (subscription.stripeStatus === 'trialing') {
            return t('billing.subscriptions.trialing');
        }

        return t('billing.subscriptions.active');
    };

    const statusVariant = (
        subscription: CurrentSubscription,
    ): 'default' | 'secondary' | 'destructive' => {
        if (subscription.cancelled && !subscription.onGracePeriod) {
            return 'destructive';
        }

        if (subscription.stripeStatus === 'trialing') {
            return 'secondary';
        }

        return 'default';
    };

    const portalUrl = getPortalUrl?.() ?? '#';

    return (
        <div className="grid gap-6 md:grid-cols-2">
            {plans.map((plan, index) => {
                const isPopular = index === 1;
                const isCurrentPlan = plan.isSubscribed;

                return (
                    <Card
                        key={plan.id}
                        className={isPopular ? 'border-primary shadow-md' : ''}
                    >
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-lg">
                                    {plan.name}
                                </CardTitle>
                                <div className="flex gap-2">
                                    {isPopular && (
                                        <Badge className="gap-1">
                                            <Sparkles className="h-3 w-3" />
                                            {t('billing.subscriptions.popular')}
                                        </Badge>
                                    )}
                                    {isCurrentPlan && currentSubscription && (
                                        <Badge
                                            variant={statusVariant(
                                                currentSubscription,
                                            )}
                                        >
                                            {statusLabel(currentSubscription)}
                                        </Badge>
                                    )}
                                </div>
                            </div>
                            <CardDescription>
                                {plan.description}
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="space-y-4">
                            <div className="flex items-baseline gap-1">
                                <span className="text-3xl font-bold">
                                    {formatPrice(plan.price)}
                                </span>
                                <span className="text-sm text-muted-foreground">
                                    {t('billing.subscriptions.per_month')}
                                </span>
                            </div>

                            <ul className="space-y-2">
                                {plan.features.map((feature) => (
                                    <li
                                        key={feature}
                                        className="flex items-center gap-2 text-sm"
                                    >
                                        <CheckCircle2 className="h-4 w-4 shrink-0 text-primary" />
                                        {feature}
                                    </li>
                                ))}
                            </ul>
                        </CardContent>

                        <CardFooter>
                            {isCurrentPlan && getPortalUrl ? (
                                <Button
                                    className="w-full"
                                    variant="outline"
                                    asChild
                                >
                                    <Link href={portalUrl}>
                                        <CreditCard className="mr-2 h-4 w-4" />
                                        {t('billing.subscriptions.manage')}
                                    </Link>
                                </Button>
                            ) : (
                                <Button
                                    className="w-full"
                                    variant={isPopular ? 'default' : 'outline'}
                                    asChild
                                >
                                    <Link
                                        href={getCheckoutUrl(
                                            plan.stripe_price_id,
                                        )}
                                    >
                                        {t('billing.subscriptions.subscribe')}
                                    </Link>
                                </Button>
                            )}
                        </CardFooter>
                    </Card>
                );
            })}
        </div>
    );
}
