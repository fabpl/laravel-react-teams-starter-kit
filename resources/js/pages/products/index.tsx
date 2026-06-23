import { Head, usePage } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import Heading from '@/components/heading';
import ProductList from '@/components/product-list';
import { useTranslation } from '@/lib/i18n';
import { index as productsIndex, checkout } from '@/routes/products';
import type { BillingProduct } from '@/types';

type Props = {
    products: BillingProduct[];
};

export default function ProductsIndex({ products }: Props) {
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

    return (
        <>
            <Head title={t('billing.products.title')} />

            <h1 className="sr-only">{t('billing.products.title')}</h1>

            <div className="flex flex-col space-y-8">
                <Heading
                    variant="small"
                    title={t('billing.products.title')}
                    description={t('billing.products.description')}
                />

                {checkoutSuccess && (
                    <div className="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-300">
                        <CheckCircle2 className="h-4 w-4 shrink-0" />
                        {t('billing.products.checkout_success')}
                    </div>
                )}

                <ProductList
                    products={products}
                    getCheckoutUrl={getCheckoutUrl}
                />
            </div>
        </>
    );
}

ProductsIndex.layout = (props: { currentTeam?: { slug: string } | null }) => ({
    breadcrumbs: [
        {
            title: 'Products',
            href: props.currentTeam
                ? productsIndex(props.currentTeam.slug).url
                : '/',
        },
    ],
});
