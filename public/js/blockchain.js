
if (typeof window.showInstallMetaMask !== 'function') {
	
	window.showInstallMetaMask = function () {
		
		alert('MetaMask não detectado. Instale a extensão MetaMask e recarregue a página.');
	};
}

if (typeof window.renderBlockchainTransactions !== 'function') {
	
	window.renderBlockchainTransactions = function (txns) {
		try {
			const container = document.getElementById('transactions') || document.getElementById('tx-list');
			if (!container) {
				console.log('[renderBlockchainTransactions] transações:', txns);
				return;
			}
			if (!Array.isArray(txns)) txns = [txns];
			container.innerHTML = txns.map(t => {
				const hash = (t && (t.hash || t.transactionHash)) ? (t.hash || t.transactionHash) : String(t);
				const from = t && t.from ? ` from: ${t.from}` : '';
				const to = t && t.to ? ` to: ${t.to}` : '';
				return `<div class="tx-item">Hash: ${hash}${from}${to}</div>`;
			}).join('');
		} catch (e) {
			console.error('[renderBlockchainTransactions] erro ao renderizar:', e);
		}
	};
}

(async function () {
    const API_CONTRACT_INFO = '/blockchain/contract-info';

    async function getContractInfo() {
        const res = await fetch(API_CONTRACT_INFO);
        if (!res.ok) throw new Error('Failed to fetch contract info');
        return res.json();
    }

   
    function loadEthersCDN(cdnUrl = 'https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js', timeoutMs = 8000) {
        if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') return Promise.resolve();
        
        if (document.querySelector('script[data-ethers-cdn]')) {
           
            const start = Date.now();
            return new Promise((resolve, reject) => {
                const id = setInterval(() => {
                    if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') { clearInterval(id); resolve(); }
                    if (Date.now() - start > timeoutMs) { clearInterval(id); reject(new Error('Timeout aguardando ethers após script existente')); }
                }, 50);
            });
        }
        return new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = cdnUrl;
            s.setAttribute('data-ethers-cdn', '1');
            let done = false;
            const t = setTimeout(() => { if (done) return; done = true; reject(new Error('Timeout carregando ethers CDN')); }, timeoutMs);
            s.onload = () => {
                if (done) return;
                setTimeout(() => {
                    if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') { clearTimeout(t); done = true; resolve(); }
                    else { clearTimeout(t); done = true; reject(new Error('ethers carregado mas providers não definidos')); }
                }, 50);
            };
            s.onerror = () => { if (done) return; done = true; clearTimeout(t); reject(new Error('Falha ao carregar ethers CDN')); };
            document.head.appendChild(s);
        });
    }


    async function waitForEthers(timeoutMs = 10000, intervalMs = 50) {
        if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') return;
        const tryLoad = Math.min(8000, Math.floor(timeoutMs * 0.8));
        try {
            await loadEthersCDN(undefined, tryLoad);
            if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') return;
        } catch (err) {
            console.warn('[waitForEthers] loadEthersCDN falhou ou timeout, caindo no polling', err);
        }
        const start = Date.now();
        return new Promise((resolve, reject) => {
            const id = setInterval(() => {
                if (window.ethers && window.ethers.providers && typeof window.ethers.providers.Web3Provider === 'function') { clearInterval(id); resolve(); }
                if (Date.now() - start > timeoutMs) { clearInterval(id); reject(new Error('Timeout aguardando ethers.providers.Web3Provider')); }
            }, intervalMs);
        });
    }


    async function ensureEthereum() {
        if (!window.ethereum) {
            throw new Error('MetaMask / Ethereum provider não encontrado no navegador');
        }

        const eth = window.ethereum;

       
        const provider = {
            isEIP1193: true,

           
            request: async function (opts) {
                if (!opts || !opts.method) throw new Error('Invalid request options');
                if (eth.request) return await eth.request(opts);
                
                if (eth.send) return await eth.send(opts.method, opts.params || []);
                throw new Error('window.ethereum não suporta request/send');
            },

            
            send: async function (method, params) {
                if (!method) throw new Error('Invalid send method');
                if (eth.request) return await eth.request({ method, params });
                if (eth.send) return await eth.send(method, params);
                throw new Error('window.ethereum não suporta request/send');
            },

            
            listAccounts: async function () {
                try {
                    if (eth.request) {
                        const accounts = await eth.request({ method: 'eth_accounts' });
                        return Array.isArray(accounts) ? accounts : [];
                    }
                    if (eth.send) {
                        const res = await eth.send('eth_accounts');
                        if (Array.isArray(res)) return res;
                        if (res && Array.isArray(res.result)) return res.result;
                    }
                    return [];
                } catch (e) {
                    throw e;
                }
            },

            
            getSigner: function () {
                return {
                    getAddress: async function () {
                        try {
                          if (eth.request) {
                            let accounts = await eth.request({ method: 'eth_accounts' });
                            if (Array.isArray(accounts) && accounts.length) return accounts[0];
                            accounts = await eth.request({ method: 'eth_requestAccounts' });
                            if (Array.isArray(accounts) && accounts.length) return accounts[0];
                            return null;
                          }
                          if (eth.send) {
                            const res = await eth.send('eth_accounts');
                            if (Array.isArray(res)) return res[0] || null;
                            if (res && res.result && Array.isArray(res.result)) return res.result[0] || null;
                          }
                          return null;
                        } catch (e) {
                          throw e;
                        }
                    }
                };
            }
        };
        return provider;
    }

    window.connectWallet = async function () {
        const statusEl = document.getElementById('metamaskStatus') || { textContent: '' };
        try {
            statusEl.textContent = 'Tentando conectar...';
            const provider = await ensureEthereum();

            let address = null;
            try {
                if (provider.request) {
                    const accounts = await provider.request({ method: 'eth_requestAccounts' });
                    if (Array.isArray(accounts) && accounts.length) address = accounts[0];
                } else if (provider.send) {
                    const res = await provider.send('eth_requestAccounts', []);
                    if (Array.isArray(res)) address = res[0];
                    else if (res && Array.isArray(res.result)) address = res.result[0];
                }
            } catch (reqErr) {
                console.warn('[connectWallet] eth_requestAccounts falhou:', reqErr);
            }

            if (!address) {
                try {
                    if (provider.getSigner) address = await provider.getSigner().getAddress();
                } catch (e) {
                    console.warn('[connectWallet] getSigner().getAddress falhou:', e);
                }
            }

            if (!address && provider.listAccounts) {
                try {
                    const accs = await provider.listAccounts();
                    if (Array.isArray(accs) && accs.length) address = accs[0];
                } catch (e) {
                    console.warn('[connectWallet] listAccounts falhou:', e);
                }
            }

            if (!address) {
                statusEl.textContent = 'Nenhuma conta conectada';
                console.warn('[connectWallet] nenhuma conta encontrada');
                return null;
            }

            statusEl.textContent = 'Carteira conectada: ' + address;
            console.log('Carteira conectada:', address);
            window.dispatchEvent(new CustomEvent('metamask:connected', { detail: { address, provider } }));
            return { address, provider };
        } catch (err) {
            console.error('Erro ao conectar carteira:', err);
            statusEl.textContent = 'Erro: ' + (err && err.message ? err.message : String(err));
            if (err && /MetaMask|Ethereum provider/i.test(err.message) && typeof window.showInstallMetaMask === 'function') {
                try { window.showInstallMetaMask(); } catch (_) {}
            }
            throw err;
        }
    };

    window.handleConnectClick = async function (evt) {
        try {
            await window.connectWallet();
        } catch (err) {
            if (err && /MetaMask|Ethereum provider/i.test(err.message)) {
                try { window.showInstallMetaMask(); } catch (_) {}
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        try {
            const btn = document.getElementById('connectMetaMaskBtn');
            if (btn && !btn._blockchainHandlerAttached) {
                btn.addEventListener('click', window.handleConnectClick);
                btn._blockchainHandlerAttached = true;
            }
        } catch (e) {
            console.warn('[blockchain.js] não foi possível ligar listener do botão:', e);
        }
    });

    window.ensureEthereum = ensureEthereum;

})(); 
