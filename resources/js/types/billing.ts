export type BillingPlan = {
    id: string;
    name: string;
    description: string;
    price: number;
    interval: string;
    stripe_price_id: string;
    features: string[];
    isSubscribed: boolean;
};

export type BillingProduct = {
    id: string;
    name: string;
    description: string;
    price: number;
    stripe_price_id: string;
};

export type CurrentSubscription = {
    type: string;
    stripeStatus: string;
    stripePrice: string | null;
    trialEndsAt: string | null;
    endsAt: string | null;
    onGracePeriod: boolean;
    cancelled: boolean;
};
