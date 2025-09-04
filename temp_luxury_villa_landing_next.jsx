import React, { useMemo, useState } from "react";
import { motion } from "framer-motion";
import {
  Calendar,
  ChevronRight,
  Heart,
  MapPin,
  Minus,
  Plus,
  Search,
  Star,
  Users,
} from "lucide-react";

// NOTE: This is a single-file React component designed for Next.js + Tailwind.
// Drop it into a page (e.g., app/page.tsx or pages/index.tsx) and ensure Tailwind is configured.
// Fonts suggestion (optional in your global): Playfair Display for headlines, Inter for body.

const LOCATIONS = [
  "Antalya",
  "Bodrum",
  "Fethiye",
  "Kaş",
  "Kalkan",
  "Marmaris",
  "Göcek",
  "Çeşme",
  "Alaçatı",
  "İzmir",
];

const CATEGORIES = [
  { key: "sea", label: "Deniz Manzaralı" },
  { key: "nature", label: "Doğa İçinde" },
  { key: "city", label: "Şehir Merkezi" },
  { key: "honeymoon", label: "Balayı" },
  { key: "heated", label: "Isıtmalı Havuz" },
];

const VILLAS = [
  {
    id: 1,
    title: "Vista Mare",
    location: "Antalya",
    category: "sea",
    beds: 4,
    price: 5200,
    rating: 4.9,
    img:
      "https://images.unsplash.com/photo-1560185127-6ed189bf04cd?auto=format&fit=crop&w=1600&q=80",
  },
  {
    id: 2,
    title: "Elysian Cove",
    location: "Bodrum",
    category: "sea",
    beds: 5,
    price: 6800,
    rating: 5,
    img:
      "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1600&q=80",
  },
  {
    id: 3,
    title: "Forest Nest",
    location: "Fethiye",
    category: "nature",
    beds: 3,
    price: 3800,
    rating: 4.8,
    img:
      "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80",
  },
  {
    id: 4,
    title: "Urban Luxe",
    location: "İzmir",
    category: "city",
    beds: 2,
    price: 3100,
    rating: 4.7,
    img:
      "https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80",
  },
  {
    id: 5,
    title: "Serenity Spa",
    location: "Göcek",
    category: "heated",
    beds: 4,
    price: 5900,
    rating: 4.9,
    img:
      "https://images.unsplash.com/photo-1559599078-1cbe3c0c9cc5?auto=format&fit=crop&w=1600&q=80",
  },
  {
    id: 6,
    title: "Moonlight Suite",
    location: "Kaş",
    category: "honeymoon",
    beds: 1,
    price: 4500,
    rating: 5,
    img:
      "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80",
  },
];

function classNames(...c) {
  return c.filter(Boolean).join(" ");
}

function LuxuryCard({ v }) {
  return (
    <div className="relative group rounded-3xl overflow-hidden shadow-xl bg-white">
      <img
        src={v.img}
        alt={v.title}
        className="h-72 w-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <button
        className="absolute top-4 right-4 p-2 rounded-full bg-white/80 backdrop-blur-md shadow hover:scale-105 transition"
        aria-label="Favorilere ekle"
      >
        <Heart className="w-5 h-5" />
      </button>
      <div className="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/70 to-transparent text-white">
        <div className="flex items-center gap-2 text-sm opacity-90">
          <MapPin className="w-4 h-4" /> {v.location} • {v.beds} Yatak Odası
        </div>
        <h3 className="text-2xl font-semibold mt-1">{v.title}</h3>
        <div className="mt-2 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <Star className="w-5 h-5" />
            <span className="font-medium">{v.rating.toFixed(1)}</span>
          </div>
          <div className="text-right">
            <div className="text-xl font-bold">₺{v.price.toLocaleString("tr-TR")} / gece</div>
            <a
              href="#"
              className="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-xl bg-white/90 text-gray-900 hover:bg-white transition"
            >
              Detay <ChevronRight className="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>
    </div>
  );
}

function DestCard({ title, img }) {
  return (
    <div className="relative overflow-hidden rounded-3xl group">
      <img src={img} alt={title} className="h-56 w-full object-cover group-hover:scale-105 transition" />
      <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
      <div className="absolute bottom-4 left-4 text-white">
        <h4 className="text-xl font-semibold">{title}</h4>
      </div>
    </div>
  );
}

export default function LuxuryVillaLanding() {
  const [sticky] = useState(true); // for semantics: header is fixed by design
  const [category, setCategory] = useState("sea");
  const [query, setQuery] = useState("");
  const [checkIn, setCheckIn] = useState("");
  const [checkOut, setCheckOut] = useState("");
  const [guests, setGuests] = useState(2);
  const [locInput, setLocInput] = useState("");
  const [showLocList, setShowLocList] = useState(false);

  const filteredLocations = useMemo(() => {
    const q = locInput.trim().toLowerCase();
    if (!q) return LOCATIONS;
    return LOCATIONS.filter((l) => l.toLowerCase().includes(q));
  }, [locInput]);

  const visibleVillas = useMemo(() => {
    return VILLAS.filter((v) => v.category === category && (!query || v.title.toLowerCase().includes(query.toLowerCase())));
  }, [category, query]);

  const handleSearch = () => {
    // In a real app, route to /search with query params
    const params = new URLSearchParams({
      q: query,
      category,
      checkIn,
      checkOut,
      guests: String(guests),
      location: locInput,
    }).toString();
    alert(`Arama yapılıyor → /search?${params}`);
  };

  return (
    <div className="min-h-screen bg-gray-50 text-gray-800">
      {/* Header */}
      <header className="w-full fixed top-0 z-50 flex justify-center pointer-events-none">
        <nav
          className={classNames(
            "mt-4 px-6 py-3 rounded-full backdrop-blur-xl shadow-lg border border-white/40 pointer-events-auto",
            "flex items-center gap-6 bg-white/70 text-gray-800",
            sticky ? "" : ""
          )}
        >
          <a href="#" className="font-medium hover:text-blue-600 transition">Ana Sayfa</a>
          <a href="#villalar" className="font-medium hover:text-blue-600 transition">Villalar</a>
          <a href="#kategoriler" className="font-medium hover:text-blue-600 transition">Kategoriler</a>
          <a href="#iletisim" className="font-medium hover:text-blue-600 transition">İletişim</a>
        </nav>
      </header>

      {/* Hero with video background */}
      <section className="relative h-[92vh] flex items-center justify-center">
        <video
          className="absolute inset-0 h-full w-full object-cover"
          autoPlay
          loop
          muted
          playsInline
          poster="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1600&q=80"
        >
          <source src="https://cdn.coverr.co/videos/coverr-luxury-villa-pool-3186/1080p.mp4" type="video/mp4" />
        </video>
        <div className="absolute inset-0 bg-gradient-to-b from-black/60 to-black/30" />

        <div className="relative z-10 max-w-6xl px-6 w-full">
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="text-white text-5xl md:text-6xl font-extrabold text-center drop-shadow"
          >
            Lüks Villalarda Unutulmaz Tatil
          </motion.h1>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="text-white/90 text-center mt-4 text-lg"
          >
            Ege ve Akdeniz’in en seçkin villalarını keşfedin. Modern mimari, özel havuz, nefes kesen manzaralar.
          </motion.p>

          {/* Glassmorphism search */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.9 }}
            className="mt-8 backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-4 md:p-6 shadow-2xl"
          >
            <div className="grid grid-cols-1 md:grid-cols-5 gap-3">
              {/* Location Autocomplete */}
              <div className="relative">
                <label className="flex items-center gap-2 text-white/90 text-sm font-medium mb-1">
                  <MapPin className="w-4 h-4" /> Konum
                </label>
                <input
                  value={locInput}
                  onChange={(e) => setLocInput(e.target.value)}
                  onFocus={() => setShowLocList(true)}
                  onBlur={() => setTimeout(() => setShowLocList(false), 120)}
                  placeholder="Nereye gitmek istersiniz?"
                  className="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 outline-none focus:ring-2 focus:ring-blue-500"
                />
                {showLocList && (
                  <div className="absolute z-20 mt-2 w-full max-h-56 overflow-auto rounded-xl bg-white shadow-lg border">
                    {filteredLocations.length === 0 && (
                      <div className="px-4 py-3 text-sm text-gray-500">Sonuç yok</div>
                    )}
                    {filteredLocations.map((l) => (
                      <button
                        key={l}
                        onMouseDown={() => setLocInput(l)}
                        className="w-full text-left px-4 py-2 hover:bg-gray-50"
                      >
                        {l}
                      </button>
                    ))}
                  </div>
                )}
              </div>

              {/* Check-in */}
              <div>
                <label className="flex items-center gap-2 text-white/90 text-sm font-medium mb-1">
                  <Calendar className="w-4 h-4" /> Giriş
                </label>
                <input
                  type="date"
                  value={checkIn}
                  onChange={(e) => setCheckIn(e.target.value)}
                  className="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              {/* Check-out */}
              <div>
                <label className="flex items-center gap-2 text-white/90 text-sm font-medium mb-1">
                  <Calendar className="w-4 h-4" /> Çıkış
                </label>
                <input
                  type="date"
                  value={checkOut}
                  onChange={(e) => setCheckOut(e.target.value)}
                  className="w-full px-4 py-3 rounded-xl bg-white/90 text-gray-800 outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              {/* Guests stepper */}
              <div>
                <label className="flex items-center gap-2 text-white/90 text-sm font-medium mb-1">
                  <Users className="w-4 h-4" /> Kişi
                </label>
                <div className="flex items-center justify-between px-3 py-2 rounded-xl bg-white/90">
                  <button
                    onClick={() => setGuests((g) => Math.max(1, g - 1))}
                    className="p-2 rounded-lg hover:bg-gray-100"
                    aria-label="Kişi azalt"
                  >
                    <Minus className="w-4 h-4" />
                  </button>
                  <div className="text-lg font-semibold">{guests}</div>
                  <button
                    onClick={() => setGuests((g) => Math.min(16, g + 1))}
                    className="p-2 rounded-lg hover:bg-gray-100"
                    aria-label="Kişi artır"
                  >
                    <Plus className="w-4 h-4" />
                  </button>
                </div>
              </div>

              {/* Search */}
              <div className="flex items-end">
                <button
                  onClick={handleSearch}
                  className="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold shadow-lg hover:opacity-95"
                >
                  <Search className="w-5 h-5" /> Ara
                </button>
              </div>
            </div>

            {/* Quick filters */}
            <div className="mt-4 flex flex-wrap items-center gap-2">
              {CATEGORIES.map((c) => (
                <button
                  key={c.key}
                  onClick={() => setCategory(c.key)}
                  className={classNames(
                    "px-4 py-2 rounded-full border text-sm",
                    category === c.key
                      ? "bg-white text-gray-900 border-white"
                      : "bg-white/20 text-white border-white/50 hover:bg-white/30"
                  )}
                >
                  {c.label}
                </button>
              ))}
              <div className="ml-auto w-full md:w-64">
                <input
                  value={query}
                  onChange={(e) => setQuery(e.target.value)}
                  placeholder="Anahtar kelime (örn. jakuzzi)"
                  className="w-full mt-2 md:mt-0 px-4 py-2 rounded-xl bg-white/90 text-gray-800 outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Featured horizontal scroll */}
      <section id="villalar" className="max-w-7xl mx-auto px-6 py-12">
        <div className="flex items-end justify-between mb-6">
          <h2 className="text-3xl md:text-4xl font-bold">Popüler Villalar</h2>
          <a href="#" className="text-blue-600 font-medium inline-flex items-center gap-1">
            Tümünü Gör <ChevronRight className="w-4 h-4" />
          </a>
        </div>
        <div className="overflow-x-auto pb-2 snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
          <div className="grid grid-flow-col auto-cols-[85%] md:auto-cols-[33%] gap-6">
            {visibleVillas.map((v) => (
              <div key={v.id} className="snap-start">
                <LuxuryCard v={v} />
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Destinations */}
      <section id="kategoriler" className="max-w-7xl mx-auto px-6 py-10">
        <h3 className="text-2xl md:text-3xl font-bold mb-6">Öne Çıkan Destinasyonlar</h3>
        <div className="grid md:grid-cols-3 gap-6">
          <DestCard
            title="Bodrum"
            img="https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=1600&q=80"
          />
          <DestCard
            title="Kaş"
            img="https://images.unsplash.com/photo-1526485797145-2f4b6b3c1a49?auto=format&fit=crop&w=1600&q=80"
          />
          <DestCard
            title="Fethiye"
            img="https://images.unsplash.com/photo-1526481280698-8fcc13fd1050?auto=format&fit=crop&w=1600&q=80"
          />
        </div>
      </section>

      {/* Testimonials */}
      <section className="bg-white/70 border-t border-b">
        <div className="max-w-7xl mx-auto px-6 py-12">
          <h3 className="text-2xl md:text-3xl font-bold mb-8">Misafir Yorumları</h3>
          <div className="grid md:grid-cols-3 gap-6">
            {[1, 2, 3].map((i) => (
              <div key={i} className="rounded-3xl p-6 bg-white shadow-lg">
                <div className="flex items-center gap-2 text-yellow-500 mb-2">
                  {Array.from({ length: 5 }).map((_, idx) => (
                    <Star key={idx} className="w-4 h-4 fill-current" />
                  ))}
                </div>
                <p className="text-gray-700">
                  "Hayatımın en huzurlu tatiliydi. Manzara harika, ev tertemiz ve her detay düşünülmüştü."
                </p>
                <div className="mt-4 text-sm text-gray-500">— A. Yılmaz, İstanbul</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Blog / Guides */}
      <section className="max-w-7xl mx-auto px-6 py-12">
        <div className="flex items-end justify-between mb-6">
          <h3 className="text-2xl md:text-3xl font-bold">Seyahat Rehberi</h3>
          <a href="#" className="text-blue-600 font-medium inline-flex items-center gap-1">
            Tüm Yazılar <ChevronRight className="w-4 h-4" />
          </a>
        </div>
        <div className="grid md:grid-cols-3 gap-6">
          {[{
            title: "Balayı için en iyi rotalar",
            img: "https://images.unsplash.com/photo-1519822471258-0a3d1873dc3f?auto=format&fit=crop&w=1600&q=80",
          }, {
            title: "Ege’de gastronomi turu",
            img: "https://images.unsplash.com/photo-1525755662778-989d0524087e?auto=format&fit=crop&w=1600&q=80",
          }, {
            title: "Çocuklu ailelere uygun villalar",
            img: "https://images.unsplash.com/photo-1505691723518-36a5ac3b2d52?auto=format&fit=crop&w=1600&q=80",
          }].map((b, idx) => (
            <div key={idx} className="group rounded-3xl overflow-hidden bg-white shadow-lg">
              <img src={b.img} alt={b.title} className="h-52 w-full object-cover group-hover:scale-105 transition" />
              <div className="p-6">
                <h4 className="text-xl font-semibold">{b.title}</h4>
                <p className="text-gray-500 mt-2">İlham verici tüyolar ve pratik öneriler.</p>
                <button className="mt-4 inline-flex items-center gap-2 text-blue-600 font-medium">
                  Devamını Oku <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-700" />
        <div className="relative max-w-7xl mx-auto px-6 py-16 text-white">
          <h3 className="text-3xl md:text-4xl font-extrabold">Hayalindeki tatil için hazır mısın?</h3>
          <p className="mt-2 text-white/90 max-w-2xl">
            Uzman danışmanlarımızla 7/24 ulaşın, sizin için en doğru villayı birlikte bulalım.
          </p>
          <div className="mt-6 flex flex-wrap gap-3">
            <a
              href="#"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-white text-blue-700 font-semibold hover:bg-gray-100"
            >
              Hemen Rezervasyon Yap
            </a>
            <a href="#" className="inline-flex items-center gap-2 px-6 py-3 rounded-2xl border border-white/60 font-semibold">
              Danışmanla Görüş
            </a>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer id="iletisim" className="bg-gray-900 text-gray-400">
        <div className="max-w-7xl mx-auto px-6 py-10">
          <div className="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
            <div>
              <div className="text-white text-xl font-bold">KiralıkVillam</div>
              <div className="text-gray-500 mt-2">© {new Date().getFullYear()} Tüm hakları saklıdır.</div>
            </div>
            <div className="text-sm text-gray-500">
              Minimal footer — Yasal, KVKK, Çerez Politikası
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}
