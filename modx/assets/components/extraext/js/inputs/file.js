extraExt.create(
	extraExt.inputs.fileinput.xtype,
	function(config) {
		config = config || {}
		Ext.applyIf(config, {
			multiple: false,
			name: 'unnamed',
			id: Ext.id(),
			text: _('file_upload') + ' <i class="fad fa-upload"></i>',
			triggerClass: 'far fa-upload'
		})
		this.input = document.createElement('input')
		this.input.type = 'file'
		this.input.name = config.name
		this.input.id = config.id + '-file'
		this.input.hidden = true
		if(config.multiple) {
			this.input.multiple = 'multiple'
		}
		this.input.addEventListener('change', () => {
			this.files = this.input.files
			var prev = []
			for(const file of this.files) {
				prev.push(file.name)
			}
			var v = this.input.value;
			this.setValue(prev.join(', '))
			this.fireEvent('fileselected', this, v);
		})
		config.name += '_'
		this._lock = 0
		extraExt.xTypes[extraExt.inputs.fileinput.xtype].superclass.constructor.call(this, config)
	},
	Ext.form.TriggerField,
	[
		{
			initComponent: function() {
				Ext.ux.form.FileUploadField.superclass.initComponent.call(this)

				this.addEvents('fileselected')
			},
			onTriggerClick: function() {
				console.debug('upload')
				// console.log(this)
				if(this._lock === 0) {
					this.el.dom.append(this.input)
					this._lock = 1
				}
				var clickEvent = new MouseEvent('click', {
					'view': window,
					'bubbles': true,
					'cancelable': false
				})
				this.input.dispatchEvent(clickEvent)
			},
			afterRender: function() {
				Ext.form.TriggerField.superclass.afterRender.call(this)
				this.updateEditState()
				// console.log(this)
			},
			getValue: function() {
				// console.log('getValue',this.files)
				return this.files
			},
			getFileInputId: function() {
				// console.log('getFileInputId',this.input.id)
				return this.input.id
			},
			reset: function() {
				if(this.rendered) {
					this.input.reset()
					this.setValue('')
				}
			},
			onDisable: function() {
				this.doDisable(true)
			},

			onEnable: function() {
				this.doDisable(false)
			},

			preFocus: Ext.emptyFn,
			doDisable: function(disabled) {
				this.input.disabled = disabled
			},
			alignErrorIcon: function() {
			}
		}
	]
)
