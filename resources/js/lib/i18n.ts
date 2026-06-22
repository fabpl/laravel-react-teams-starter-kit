import { usePage } from '@inertiajs/react';

export function useTranslation() {
    const { translations } = usePage().props;

    const t = (key: string, replacements?: Record<string, string>): string => {
        const dotIndex = key.indexOf('.');
        const namespace = dotIndex === -1 ? key : key.slice(0, dotIndex);
        const subkey = dotIndex === -1 ? '' : key.slice(dotIndex + 1);
        const group = translations[namespace];
        let value: string =
            (group && subkey ? group[subkey] : undefined) ?? key;

        if (replacements) {
            for (const [placeholder, replacement] of Object.entries(
                replacements,
            )) {
                value = value.replaceAll(`:${placeholder}`, replacement);
            }
        }

        return value;
    };

    return { t } as const;
}
