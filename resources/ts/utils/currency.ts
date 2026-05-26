export interface CurrencyDef {
  symbol: string
  decimals: number
  position: 'before' | 'after'
  label: string
}

export const CURRENCIES: Record<string, CurrencyDef> = {
  TRY: { symbol: '₺',   decimals: 2, position: 'before', label: 'Türk Lirası (₺)'              },
  IQD: { symbol: 'IQD', decimals: 0, position: 'after',  label: 'Irak Dinarı (IQD)'            },
  USD: { symbol: '$',   decimals: 2, position: 'before', label: 'Amerikan Doları ($)'           },
  EUR: { symbol: '€',   decimals: 2, position: 'before', label: 'Euro (€)'                     },
  GBP: { symbol: '£',   decimals: 2, position: 'before', label: 'İngiliz Sterlini (£)'         },
  SAR: { symbol: 'SAR', decimals: 2, position: 'after',  label: 'Suudi Arabistan Riyali (SAR)' },
  AED: { symbol: 'AED', decimals: 2, position: 'after',  label: 'BAE Dirhemi (AED)'            },
  KWD: { symbol: 'KWD', decimals: 3, position: 'after',  label: 'Kuveyt Dinarı (KWD)'          },
  SYP: { symbol: 'SP',  decimals: 0, position: 'after',  label: 'Suriye Lirası (SP)'           },
}

export function formatPrice(amount: number, currencyCode = 'TRY'): string {
  const curr = CURRENCIES[currencyCode] ?? CURRENCIES.TRY
  let formatted = amount.toLocaleString('tr-TR', {
    minimumFractionDigits: curr.decimals,
    maximumFractionDigits: curr.decimals,
  })
  // Trailing ,00 / ,000 kaldır (örn: 1.000,00 → 1.000)
  formatted = formatted.replace(/,0+$/, '')
  return curr.position === 'before'
    ? `${curr.symbol}${formatted}`
    : `${formatted} ${curr.symbol}`
}
