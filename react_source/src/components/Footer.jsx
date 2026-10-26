import React from 'react';
import { ShowerHead as WashingMachine, Phone, Mail, Clock, Facebook, Instagram } from 'lucide-react';

const Footer = () => {
  const socialLinks = [
    { icon: Facebook, href: '#', name: 'Facebook' },
    { icon: Instagram, href: '#', name: 'Instagram' },
  ];

  return (
    <footer className="bg-gradient-to-br from-gray-900 via-gray-800 to-green-900 text-white py-16 px-4">
      <div className="container mx-auto">
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
          <div>
            <div className="flex items-center gap-3 mb-4">
              <div className="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-full flex items-center justify-center">
                <WashingMachine className="w-7 h-7 text-white" />
              </div>
              <span className="text-2xl font-serif font-bold">CROWN & LUCKY</span>
            </div>
            <p className="text-gray-300 leading-relaxed pr-4">
              Pengalaman dan Kualitas Terpercaya Sejak 1997.
            </p>
            <div className="flex items-center gap-4 mt-6">
              {socialLinks.map(link => (
                <a key={link.name} href={link.href} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-white transition">
                  <link.icon className="w-6 h-6" />
                  <span className="sr-only">{link.name}</span>
                </a>
              ))}
            </div>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4 uppercase tracking-wider">Kontak Kami</h3>
            <div className="space-y-4">
              <div className="flex items-center gap-3">
                <Phone className="w-5 h-5 text-emerald-400 flex-shrink-0" />
                <span className="text-gray-300 hover:text-white transition">+62 822 3377 59394</span>
              </div>
               <div className="flex items-center gap-3">
                <Phone className="w-5 h-5 text-emerald-400 flex-shrink-0" />
                <span className="text-gray-300 hover:text-white transition">(+6231) 867 9336</span>
              </div>
              <div className="flex items-center gap-3">
                <Mail className="w-5 h-5 text-emerald-400 flex-shrink-0" />
                <span className="text-gray-300 hover:text-white transition">crownlaundry.service@gmail.com</span>
              </div>
            </div>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4 uppercase tracking-wider">Jam Operasional</h3>
            <div className="flex items-start gap-3">
              <Clock className="w-5 h-5 text-emerald-400 flex-shrink-0 mt-1" />
              <div className="text-gray-300">
                <p><span className="font-semibold">Senin - Kamis:</span> 08:00 - 19:00</p>
                <p><span className="font-semibold">Jumat - Minggu:</span> 08:00 - 18:00</p>
              </div>
            </div>
          </div>

          <div>
            <h3 className="text-lg font-bold mb-4 uppercase tracking-wider">Alamat Kami</h3>
            <p className="text-gray-300 leading-relaxed">
              Jl. Abd. Rahman BLK No. 98 Pabean, Sedati, Sidoarjo
            </p>
          </div>
        </div>

        <div className="border-t border-gray-700 pt-8 text-center">
          <p className="text-gray-400">
            © 2025 CROWN & LUCKY Laundry. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
};
export default Footer;