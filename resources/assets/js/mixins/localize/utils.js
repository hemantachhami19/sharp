export const LocalizableFields = [
    'text', 'markdown', 'textarea', 'wysiwyg', 'select', 'autocomplete', 'tags'
];
export const LocalizableOptionsFields = [
    'select', 'autocomplete', 'tags'
];
export const LocalizableValueFields = [
    'text', 'textarea', //'markdown', 'wysiwyg'
];

export function isLocaleObject(obj, locales) {
    return obj && typeof obj === 'object' && Object.keys(obj).every(key => locales.includes(key));
}

export function isLocalizableValueField(field) {
    return LocalizableValueFields.includes(field.type);
}

export function localeObject({ locales, resolve=()=>null }) {
    return locales.reduce((res, locale)=>({
        ...res, [locale]: resolve(locale)
    }), {});
}

