import React from 'react';

export default function Container({ children }) {
    return (
        <div className="bg-gray-100 dark:bg-gray-800 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
            {children}
        </div>
    );
}
