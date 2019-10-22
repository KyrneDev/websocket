import SettingsModal from 'flarum/components/SettingsModal';

export default class PusherSettingsModal extends SettingsModal {
  className() {
    return 'PusherSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('hyn-websocket.admin.pusher_settings.title');
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('hyn-websocket.admin.pusher_settings.app_id_label')}</label>
        <input className="FormControl" bidi={this.setting('hyn-websocket.app_id')}/>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('hyn-websocket.admin.pusher_settings.app_key_label')}</label>
        <input className="FormControl" bidi={this.setting('hyn-websocket.app_key')}/>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('hyn-websocket.admin.pusher_settings.app_secret_label')}</label>
        <input className="FormControl" bidi={this.setting('hyn-websocket.app_secret')}/>
      </div>,

      <div className="Form-group">
          <label>{app.translator.trans('hyn-websocket.admin.pusher_settings.app_host_label')}</label>
          <input className="FormControl" bidi={this.setting('hyn-websocket.app_host')}/>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('hyn-websocket.admin.pusher_settings.app_port_label')}</label>
        <input className="FormControl" bidi={this.setting('hyn-websocket.app_port')}/>
      </div>
    ];
  }
}
