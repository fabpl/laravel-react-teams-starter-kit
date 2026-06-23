import { Link } from '@inertiajs/react';
import { ShoppingBag } from 'lucide-react';
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
import type { BillingProduct } from '@/types';

function formatPrice(cents: number): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
    }).format(cents / 100);
}

type Props = {
    products: BillingProduct[];
    getCheckoutUrl: (priceId: string) => string;
};

export default function ProductList({ products, getCheckoutUrl }: Props) {
    const { t } = useTranslation();

    return (
        <div className="grid gap-6 md:grid-cols-2">
            {products.map((product) => (
                <Card key={product.id}>
                    <CardHeader>
                        <div className="flex items-start justify-between gap-2">
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                <ShoppingBag className="h-5 w-5 text-primary" />
                            </div>
                            <Badge variant="secondary">
                                {t('billing.products.one_time')}
                            </Badge>
                        </div>
                        <CardTitle className="text-lg">
                            {product.name}
                        </CardTitle>
                        <CardDescription>{product.description}</CardDescription>
                    </CardHeader>

                    <CardContent>
                        <div className="text-3xl font-bold">
                            {formatPrice(product.price)}
                        </div>
                    </CardContent>

                    <CardFooter>
                        <Button className="w-full" asChild>
                            <Link
                                href={getCheckoutUrl(product.stripe_price_id)}
                            >
                                {t('billing.products.buy')}
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>
            ))}
        </div>
    );
}
