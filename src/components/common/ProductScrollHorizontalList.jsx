import React from 'react';


export default function ProductScrollHorizontalList({ children }) {
    return (
        <div className="overflow-x-auto w-full">
            <div className="flex space-x-4">
                {children}
            </div>
        </div>
    );
}
