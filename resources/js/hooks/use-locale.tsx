import { usePage } from '@inertiajs/react';

export type Locale = 'en' | 'fr';

const setCookie = (name: string, value: string, days = 365): void => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function useLocale() {
    const { locale, supportedLocales } = usePage().props;

    const updateLocale = (newLocale: string): void => {
        setCookie('locale', newLocale);
        window.location.reload();
    };

    return { locale, supportedLocales, updateLocale } as const;
}
