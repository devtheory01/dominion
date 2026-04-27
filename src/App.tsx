import { LayoutGrid, Database, UploadCloud, Github } from 'lucide-react';

export default function App() {
  return (
    <div className="min-h-screen bg-[#F4F4F7] flex flex-col items-center justify-center p-6 text-[#1A1A1A] font-sans">
      <div className="max-w-2xl w-full bg-white rounded-2xl shadow-xl border border-gray-200 p-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div className="flex items-center gap-4">
          <div className="bg-[#C00000] w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg ring-4 ring-red-50">D</div>
          <div>
            <h1 className="text-2xl font-black tracking-tight leading-none">CHURCH MANAGER</h1>
            <p className="text-xs text-[#C00000] font-black uppercase tracking-widest mt-1">PHP + MySQL Source Code</p>
          </div>
        </div>

        <div className="space-y-4">
          <div className="p-4 bg-blue-50 border border-blue-100 rounded-xl flex gap-4 items-start">
            <LayoutGrid className="w-6 h-6 text-blue-600 shrink-0 mt-1" />
            <div>
              <h3 className="font-bold text-sm text-blue-900">Developer Environment Preview</h3>
              <p className="text-xs text-blue-800/80 leading-relaxed">
                This environment runs Node.js, so the PHP code cannot be executed locally here. 
                All your church management files (index.php, admin/, includes/) are ready to be exported.
              </p>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="p-4 bg-gray-50 border border-gray-100 rounded-xl">
              <Github className="w-5 h-5 text-gray-400 mb-2" />
              <h4 className="font-bold text-xs uppercase tracking-wider text-gray-500 mb-1">GitHub Status</h4>
              <p className="text-xs font-medium">Ready for Repo Sync</p>
            </div>
            <div className="p-4 bg-gray-50 border border-gray-100 rounded-xl">
              <UploadCloud className="w-5 h-5 text-gray-400 mb-2" />
              <h4 className="font-bold text-xs uppercase tracking-wider text-gray-500 mb-1">Target Host</h4>
              <p className="text-xs font-medium">InfinityFree (LAMP)</p>
            </div>
          </div>
        </div>

        <div className="pt-6 border-t border-gray-100">
          <h3 className="text-sm font-black uppercase tracking-widest text-[#1A1A1A] mb-4">How to host on InfinityFree:</h3>
          <ol className="space-y-3">
            {[
              "Go to your GitHub repo and click 'Download ZIP' from the Code button.",
              "Log into your InfinityFree account and open the File Manager.",
              "Upload all files into the 'htdocs' folder.",
              "Create a MySQL database in the InfinityFree Control Panel.",
              "Edit 'includes/db.php' with your database host, name, user, and password.",
              "Visit 'your-website.com/install.php' to setup the database tables."
            ].map((step, i) => (
              <li key={i} className="flex gap-3 text-xs leading-relaxed">
                <span className="font-black text-[#C00000] shrink-0">{i + 1}.</span>
                <span className="text-gray-600 font-medium">{step}</span>
              </li>
            ))}
          </ol>
        </div>

        <button 
          onClick={() => window.open('https://github.com', '_blank')}
          className="w-full bg-[#1A1A1A] text-white py-4 rounded-xl font-bold uppercase tracking-wider text-sm hover:bg-black transition-colors flex items-center justify-center gap-2"
        >
          <Github className="w-4 h-4" /> Go to GitHub Repo
        </button>
      </div>
      
      <p className="mt-8 text-[10px] font-black uppercase tracking-widest text-gray-400">
        Built with Google AI Studio &bull; Professional Church Suite v1.0
      </p>
    </div>
  );
}
