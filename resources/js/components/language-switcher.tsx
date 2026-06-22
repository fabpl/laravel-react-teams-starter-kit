import { Languages } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useLocale } from '@/hooks/use-locale';
import { cn } from '@/lib/utils';

const localeLabels: Record<string, string> = {
    en: 'English',
    fr: 'Français',
};

export function LanguageSwitcher({ className }: { className?: string }) {
    const { locale, supportedLocales, updateLocale } = useLocale();

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    size="icon"
                    className={cn('group h-9 w-9 cursor-pointer', className)}
                    aria-label="Switch language"
                >
                    <Languages className="size-5! opacity-80 group-hover:opacity-100" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                {supportedLocales.map((l) => (
                    <DropdownMenuItem
                        key={l}
                        onClick={() => updateLocale(l)}
                        className={cn(
                            'cursor-pointer',
                            locale === l && 'font-medium text-foreground',
                        )}
                    >
                        {localeLabels[l] ?? l.toUpperCase()}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
