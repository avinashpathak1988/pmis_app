function array(t){for(i=0;i<t;i++)this[i]=0;this.length=t}function integer(t){return t%4294967296}function shr(t,r){return t=integer(t),r=integer(r),t-2147483648>=0?(t%=2147483648,t>>=r,t+=1073741824>>r-1):t>>=r,t}function shl1(t){return t%=2147483648,t&!0?(t-=1073741824,t*=2,t+=2147483648):t*=2,t}function shl(t,r){t=integer(t),r=integer(r);for(var n=0;r>n;n++)t=shl1(t);return t}function and(t,r){t=integer(t),r=integer(r);var n=t-2147483648,e=r-2147483648;return n>=0?e>=0?(n&e)+2147483648:n&r:e>=0?t&e:t&r}function or(t,r){t=integer(t),r=integer(r);var n=t-2147483648,e=r-2147483648;return n>=0?e>=0?(n|e)+2147483648:(n|r)+2147483648:e>=0?(t|e)+2147483648:t|r}function xor(t,r){t=integer(t),r=integer(r);var n=t-2147483648,e=r-2147483648;return n>=0?e>=0?n^e:(n^r)+2147483648:e>=0?(t^e)+2147483648:t^r}function not(t){return t=integer(t),4294967295-t}function F(t,r,n){return or(and(t,r),and(not(t),n))}function G(t,r,n){return or(and(t,n),and(r,not(n)))}function H(t,r,n){return xor(xor(t,r),n)}function I(t,r,n){return xor(r,or(t,not(n)))}function rotateLeft(t,r){return or(shl(t,r),shr(t,32-r))}function FF(t,r,n,e,i,a,S){return t=t+F(r,n,e)+i+S,t=rotateLeft(t,a),t+=r}function GG(t,r,n,e,i,a,S){return t=t+G(r,n,e)+i+S,t=rotateLeft(t,a),t+=r}function HH(t,r,n,e,i,a,S){return t=t+H(r,n,e)+i+S,t=rotateLeft(t,a),t+=r}function II(t,r,n,e,i,a,S){return t=t+I(r,n,e)+i+S,t=rotateLeft(t,a),t+=r}function transform(t,r){var n=0,e=0,a=0,S=0,o=transformBuffer;for(n=state[0],e=state[1],a=state[2],S=state[3],i=0;i<16;i++)for(o[i]=and(t[4*i+r],255),j=1;j<4;j++)o[i]+=shl(and(t[4*i+j+r],255),8*j);n=FF(n,e,a,S,o[0],S11,3614090360),S=FF(S,n,e,a,o[1],S12,3905402710),a=FF(a,S,n,e,o[2],S13,606105819),e=FF(e,a,S,n,o[3],S14,3250441966),n=FF(n,e,a,S,o[4],S11,4118548399),S=FF(S,n,e,a,o[5],S12,1200080426),a=FF(a,S,n,e,o[6],S13,2821735955),e=FF(e,a,S,n,o[7],S14,4249261313),n=FF(n,e,a,S,o[8],S11,1770035416),S=FF(S,n,e,a,o[9],S12,2336552879),a=FF(a,S,n,e,o[10],S13,4294925233),e=FF(e,a,S,n,o[11],S14,2304563134),n=FF(n,e,a,S,o[12],S11,1804603682),S=FF(S,n,e,a,o[13],S12,4254626195),a=FF(a,S,n,e,o[14],S13,2792965006),e=FF(e,a,S,n,o[15],S14,1236535329),n=GG(n,e,a,S,o[1],S21,4129170786),S=GG(S,n,e,a,o[6],S22,3225465664),a=GG(a,S,n,e,o[11],S23,643717713),e=GG(e,a,S,n,o[0],S24,3921069994),n=GG(n,e,a,S,o[5],S21,3593408605),S=GG(S,n,e,a,o[10],S22,38016083),a=GG(a,S,n,e,o[15],S23,3634488961),e=GG(e,a,S,n,o[4],S24,3889429448),n=GG(n,e,a,S,o[9],S21,568446438),S=GG(S,n,e,a,o[14],S22,3275163606),a=GG(a,S,n,e,o[3],S23,4107603335),e=GG(e,a,S,n,o[8],S24,1163531501),n=GG(n,e,a,S,o[13],S21,2850285829),S=GG(S,n,e,a,o[2],S22,4243563512),a=GG(a,S,n,e,o[7],S23,1735328473),e=GG(e,a,S,n,o[12],S24,2368359562),n=HH(n,e,a,S,o[5],S31,4294588738),S=HH(S,n,e,a,o[8],S32,2272392833),a=HH(a,S,n,e,o[11],S33,1839030562),e=HH(e,a,S,n,o[14],S34,4259657740),n=HH(n,e,a,S,o[1],S31,2763975236),S=HH(S,n,e,a,o[4],S32,1272893353),a=HH(a,S,n,e,o[7],S33,4139469664),e=HH(e,a,S,n,o[10],S34,3200236656),n=HH(n,e,a,S,o[13],S31,681279174),S=HH(S,n,e,a,o[0],S32,3936430074),a=HH(a,S,n,e,o[3],S33,3572445317),e=HH(e,a,S,n,o[6],S34,76029189),n=HH(n,e,a,S,o[9],S31,3654602809),S=HH(S,n,e,a,o[12],S32,3873151461),a=HH(a,S,n,e,o[15],S33,530742520),e=HH(e,a,S,n,o[2],S34,3299628645),n=II(n,e,a,S,o[0],S41,4096336452),S=II(S,n,e,a,o[7],S42,1126891415),a=II(a,S,n,e,o[14],S43,2878612391),e=II(e,a,S,n,o[5],S44,4237533241),n=II(n,e,a,S,o[12],S41,1700485571),S=II(S,n,e,a,o[3],S42,2399980690),a=II(a,S,n,e,o[10],S43,4293915773),e=II(e,a,S,n,o[1],S44,2240044497),n=II(n,e,a,S,o[8],S41,1873313359),S=II(S,n,e,a,o[15],S42,4264355552),a=II(a,S,n,e,o[6],S43,2734768916),e=II(e,a,S,n,o[13],S44,1309151649),n=II(n,e,a,S,o[4],S41,4149444226),S=II(S,n,e,a,o[11],S42,3174756917),a=II(a,S,n,e,o[2],S43,718787259),e=II(e,a,S,n,o[9],S44,3951481745),state[0]+=n,state[1]+=e,state[2]+=a,state[3]+=S}function init(){for(count[0]=count[1]=0,state[0]=1732584193,state[1]=4023233417,state[2]=2562383102,state[3]=271733878,i=0;i<digestBits.length;i++)digestBits[i]=0}function update(t){var r;r=and(shr(count[0],3),63),count[0]<4294967288?count[0]+=8:(count[1]++,count[0]-=4294967296,count[0]+=8),buffer[r]=and(t,255),r>=63&&transform(buffer,0)}function finish(){var t,r=new array(8),n=0,e=0,i=0;for(n=0;4>n;n++)r[n]=and(shr(count[0],8*n),255);for(n=0;4>n;n++)r[n+4]=and(shr(count[1],8*n),255);for(e=and(shr(count[0],3),63),i=56>e?56-e:120-e,t=new array(64),t[0]=128,n=0;i>n;n++)update(t[n]);for(n=0;8>n;n++)update(r[n]);for(n=0;4>n;n++)for(j=0;j<4;j++)digestBits[4*n+j]=and(shr(state[n],8*j),255)}function hexa(t){var r="0123456789abcdef",n="",e=t;for(hexa_i=0;hexa_i<8;hexa_i++)n=r.charAt(Math.abs(e)%16)+n,e=Math.floor(e/16);return n}function MD5(t){var r,n,e,a,S,o,u;for(init(),e=0;e<t.length;e++)r=t.charAt(e),update(ascii.lastIndexOf(r));for(finish(),a=S=o=u=0,i=0;i<4;i++)a+=shl(digestBits[15-i],8*i);for(i=4;i<8;i++)S+=shl(digestBits[15-i],8*(i-4));for(i=8;i<12;i++)o+=shl(digestBits[15-i],8*(i-8));for(i=12;i<16;i++)u+=shl(digestBits[15-i],8*(i-12));return n=hexa(u)+hexa(o)+hexa(S)+hexa(a)}var state=new array(4),count=new array(2);count[0]=0,count[1]=0;var buffer=new array(64),transformBuffer=new array(16),digestBits=new array(16),S11=7,S12=12,S13=17,S14=22,S21=5,S22=9,S23=14,S24=20,S31=4,S32=11,S33=16,S34=23,S41=6,S42=10,S43=15,S44=21,ascii="01234567890123456789012345678901 !\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";