// form (input)

import React from "react";

const FormNumberInput = ({ judul, jumlahInput }) => {
    const handleInputChange = (e, index) => {
        const value = e.target.value;
        const formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        document.getElementById(`input-${index}`).value = formattedValue;
    };

    return (
        <div className="w-full max-w-xl mx-auto p-4">
            <h2 className="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{judul}</h2>
            <div className="space-y-4">
                {Array.from({ length: jumlahInput }).map((_, index) => (
                    <input
                        key={index}
                        id={`input-${index}`}
                        type="text"
                        inputMode="numeric"
                        pattern="[0-9]*"
                        className="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                        onChange={(e) => handleInputChange(e, index)}
                        placeholder="Masukkan angka"
                    />
                ))}
            </div>
        </div>
    );
};

export default FormNumberInput;
