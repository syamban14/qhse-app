@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-qhse-primary dark:focus:border-qhse-secondary focus:ring-qhse-primary dark:focus:ring-qhse-secondary rounded-md shadow-sm']) }}>
