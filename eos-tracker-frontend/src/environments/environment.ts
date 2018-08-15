// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  walletUrl: 'https://feesimplewallet.io',
  votingUrl: '',
  appName: 'FeeSimple XFS Tracker',
  logoUrl: '/assets/logo.png',
  apiUrl: 'https://feesimpletracker.io:444',
  blockchainUrl: 'https://feesimpletracker.io:8878',
 chainId: '1c6ae7719a2a3b4ecb19584a30ff510ba1b6ded86e1fd8b8fc22f1179c622a32',
 useChain: false
};
