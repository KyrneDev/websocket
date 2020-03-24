import {settings} from '@fof-components';

const {
    SettingsModal,
    items: {BooleanItem},
} = settings;

export default class PusherSettingsModal extends SettingsModal {
    className() {
        return 'PusherSettingsModal Modal--small';
    }

    title() {
        return app.translator.trans('kyrne-websocket.admin.pusher_settings.title');
    }

    form() {
        return [
            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.app_id_label')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.app_id')}</div>
                <input className="FormControl" bidi={this.setting('kyrne-websocket.app_id')}/>
            </div>,

            <div className="Form-group">

                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.app_key_label')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.app_key')}</div>
                <input className="FormControl" bidi={this.setting('kyrne-websocket.app_key')}/>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.app_secret_label')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.secret')}</div>
                <input className="FormControl" bidi={this.setting('kyrne-websocket.app_secret')}/>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.app_host_label')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.host')}</div>
                <input className="FormControl" placeholder={window.location.hostname} bidi={this.setting('kyrne-websocket.app_host')}/>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.app_port_label')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.port')}</div>
                <input className="FormControl" placeholder="6001" bidi={this.setting('kyrne-websocket.app_port')}/>
            </div>,

            <div className="Form-group">
                <BooleanItem key={'kyrne-websocket.reverse_proxy'} cast={Number}>
                    <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.reverse_proxy')}</label>
                </BooleanItem>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.reverse_proxy')}</div>
            </div>,
            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.local_cert_path')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.cert_path')}</div>
                <input className="FormControl" placeholder="/etc/letsencrypt/live/your.domain/fullchain.pem" bidi={this.setting('kyrne-websocket.local_cert')}/>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.local_pk_path')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.pk_path')}</div>
                <input className="FormControl" placeholder="/etc/letsencrypt/live/your.domain/privkey.pem" bidi={this.setting('kyrne-websocket.local_pk')}/>
            </div>,

            <div className="Form-group">
                <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.passphrase')}</label>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.passphrase')}</div>
                <input className="FormControl" bidi={this.setting('kyrne-websocket.passphrase')}/>
            </div>,

            <div className="Form-group">
                <BooleanItem key={'kyrne-websocket.cert_self_signed'} cast={Number}>
                    <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.cert_self_signed')}</label>
                </BooleanItem>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.self_signed')}</div>
            </div>,

            <div className="Form-group">
                <BooleanItem key={'kyrne-websocket.force_secure'} cast={Number}>
                    <label>{app.translator.trans('kyrne-websocket.admin.pusher_settings.secure')}</label>
                </BooleanItem>
                <div className='helpText'>{app.translator.trans('kyrne-websocket.admin.help.secure')}</div>
            </div>
        ];
    }
}
