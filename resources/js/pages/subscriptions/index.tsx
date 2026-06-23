import { Head, Link, usePage } from '@inertiajs/react';
import { CheckCircle2, CreditCard } from 'lucide-react';
import { useEffect, useState } from 'react';
import Heading from '@/components/heading';
import PlanList from '@/components/plan-list';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/lib/i18n';
import {
    index as subscriptionsIndex,
    checkout,
    portal,
} from '@/routes/subscriptions';
import type { BillingPlan, CurrentSubscription } from '@/types';

type Props = {
    plans: BillingPlan[];
    currentSubscription: CurrentSubscription | null;
};

export default function SubscriptionsIndex({
    plans,
    currentSubscription,
}: Props) {
    const page = usePage();
    const { t } = useTranslation();
    const { currentTeam } = page.props;
    const [checkoutSuccess] = useState(
        () =>
            typeof window !== 'undefined' &&
            new URL(window.location.href).searchParams.get('checkout') ===
                'success',
    );

    useEffect(() => {
        if (checkoutSuccess) {
            const url = new URL(window.location.href);
            url.searchParams.delete('checkout');
            window.history.replaceState({}, '', url.toString());
        }
    }, [checkoutSuccess]);

    const getCheckoutUrl = (priceId: string) =>
        currentTeam
            ? checkout({ current_team: currentTeam.slug, priceId }).url
            : '#';

    const getPortalUrl = currentTeam
        ? () => portal(currentTeam.slug).url
        : undefined;

    return (
        <>
            <Head title={t('billing.subscriptions.title')} />

            <h1 className="sr-only">{t('billing.subscriptions.title')}</h1>

            <div className="flex flex-col space-y-8">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title={t('billing.subscriptions.title')}
                        description={t('billing.subscriptions.description')}
                    />

                    {currentSubscription && currentTeam && (
                        <Button variant="outline" asChild>
                            <Link href={portal(currentTeam.slug).url}>
                                <CreditCard className="mr-2 h-4 w-4" />
                                {t('billing.subscriptions.manage')}
                            </Link>
                        </Button>
                    )}
                </div>

                {checkoutSuccess && (
                    <div className="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-300">
                        <CheckCircle2 className="h-4 w-4 shrink-0" />
                        {t('billing.subscriptions.checkout_success')}
                    </div>
                )}

                {currentSubscription?.onGracePeriod &&
                    currentSubscription.endsAt && (
                        <div className="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-300">
                            {t('billing.subscriptions.on_grace_period', {
                                date: new Date(
                                    currentSubscription.endsAt,
                                ).toLocaleDateString(),
                            })}
                        </div>
                    )}

                <PlanList
                    plans={plans}
                    currentSubscription={currentSubscription}
                    getCheckoutUrl={getCheckoutUrl}
                    getPortalUrl={getPortalUrl}
                />
            </div>
        </>
    );
}

SubscriptionsIndex.layout = (props: {
    currentTeam?: { slug: string } | null;
}) => ({
    breadcrumbs: [
        {
            title: 'Subscriptions',
            href: props.currentTeam
                ? subscriptionsIndex(props.currentTeam.slug).url
                : '/',
        },
    ],
});
