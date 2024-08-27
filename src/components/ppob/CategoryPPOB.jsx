// Rekening listrik(postpaid dan nontaglist)
// Listrik pra bayar.
// Rekening telepon rumah / kantor(JASTEL)
// TV Kabel(Indiehome, Trans Vision, dsb)
// Pembelian paket data(simpati, XL, Indosat, dll)
// Pembelian voucher pulsa(Simpati, XL, Indosat, IM3, dll)

import React, { useState } from "react";

const TabView = ({ tabs }) => {
    const [activeTab, setActiveTab] = useState(0);

    return (
        <div className="w-full max-w-4xl mx-auto p-4">
            {/* Tab Labels */}
            <div className="flex justify-center space-x-4">
                {tabs.map((tab, index) => (
                    <button
                        key={index}
                        className={`py-2 px-4 rounded-md text-lg font-medium transition-colors ${activeTab === index
                            ? "bg-indigo-600 text-white"
                            : "bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
                            }`}
                        onClick={() => setActiveTab(index)}
                    >
                        {tab.label}
                    </button>
                ))}
            </div>

            {/* Tab Content */}
            <div className="mt-8">
                {tabs.map((tab, index) => (
                    <div
                        key={index}
                        className={`${activeTab === index ? "block" : "hidden"
                            } text-center`}
                    >
                        <img
                            src={tab.image}
                            alt={tab.label}
                            className="mx-auto w-64 h-64 object-cover rounded-md"
                        />
                        <p className="mt-4 text-lg text-gray-800 dark:text-gray-300">
                            {tab.text}
                        </p>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default TabView;
