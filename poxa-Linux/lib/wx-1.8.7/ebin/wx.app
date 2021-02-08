%% This is an -*- erlang -*- file.
%%
%% %CopyrightBegin%
%%
%% Copyright Ericsson AB 2010-2016. All Rights Reserved.
%%
%% Licensed under the Apache License, Version 2.0 (the "License");
%% you may not use this file except in compliance with the License.
%% You may obtain a copy of the License at
%%
%%     http://www.apache.org/licenses/LICENSE-2.0
%%
%% Unless required by applicable law or agreed to in writing, software
%% distributed under the License is distributed on an "AS IS" BASIS,
%% WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
%% See the License for the specific language governing permissions and
%% limitations under the License.
%%
%% %CopyrightEnd%

{application, wx,
 [{description, "Yet another graphics system"},
  {vsn, "1.8.7"},
  {modules,
   [
    %% Generated modules
  wxAuiNotebookEvent, wxStaticBoxSizer, wxUpdateUIEvent, wxIcon, wxColourPickerEvent, wxBitmapButton, wxImage, wxGraphicsContext, wxFontPickerCtrl, wxEvtHandler, wxFileDialog, wxFlexGridSizer, wxPrintDialogData, wxColourData, wxDisplay, wxDCOverlay, wxClipboardTextEvent, wxMoveEvent, wxChoicebook, wxSystemOptions, wxGridCellFloatRenderer, wxWindowDC, wxColourDialog, wxHtmlLinkEvent, wxStatusBar, wxInitDialogEvent, wxEraseEvent, wxXmlResource, wxTaskBarIconEvent, wxGraphicsObject, wxPrintout, wxSysColourChangedEvent, wxGridCellRenderer, wxListCtrl, wxLocale, wxGraphicsMatrix, wxPreviewFrame, wxBitmap, wxRegion, wxSizerItem, wxFrame, wxNavigationKeyEvent, wxGraphicsRenderer, wxGridCellBoolRenderer, wxMouseCaptureLostEvent, wxTextEntryDialog, wxIdleEvent, wxStyledTextCtrl, wxListItem, wxSpinCtrl, wxGLCanvas, wxMDIClientWindow, wxMDIChildFrame, wxStdDialogButtonSizer, wxPrintPreview, wxPrintData, wxDirPickerCtrl, wxKeyEvent, wxEvent, wxRadioBox, wxCalendarDateAttr, wxMessageDialog, wxTreebook, wxLogNull, wxScrollWinEvent, wxCalendarCtrl, wxGraphicsBrush, wxDC, wxWindowDestroyEvent, wxSetCursorEvent, wxFontDialog, wxChoice, wxControl, wxActivateEvent, wxGraphicsFont, wxStaticText, wxIconizeEvent, wxPostScriptDC, wxJoystickEvent, wxPrinter, wxStaticBitmap, wxGridBagSizer, wxListbook, wxGridSizer, wxScrollEvent, wx_misc, wxWindowCreateEvent, wxGridCellFloatEditor, wxStyledTextEvent, wxPrintDialog, wxStaticBox, wxBufferedDC, wxTextCtrl, wxDropFilesEvent, wxDateEvent, wxGridCellAttr, wxCalendarEvent, wxGauge, wxSizerFlags, wxGridCellTextEditor, wxShowEvent, wxBitmapDataObject, wxGBSizerItem, wxFindReplaceDialog, wxTextDataObject, wxStaticLine, wxMiniFrame, wxListEvent, wxCursor, wxDialog, wxPaintDC, wxScreenDC, wxFileDataObject, wxPopupWindow, wxColourPickerCtrl, wxFilePickerCtrl, wxGrid, wxAuiSimpleTabArt, wxSashEvent, wxMask, wxFontData, wxScrollBar, wxMenuEvent, wxCheckBox, wxHtmlWindow, wxPaletteChangedEvent, wxQueryNewPaletteEvent, wxListItemAttr, wxMirrorDC, wxAuiManager, wxBoxSizer, wxMouseCaptureChangedEvent, wxClipboard, wxMouseEvent, wxDirDialog, wxMenu, wxAuiPaneInfo, wxPaintEvent, wxSplitterWindow, wxProgressDialog, wxListBox, wxNotebookEvent, wxFileDirPickerEvent, wxMenuItem, wxChildFocusEvent, wxButton, wxDisplayChangedEvent, wxToggleButton, wxToolBar, wxGraphicsPen, wxGridCellNumberRenderer, wxNotifyEvent, wxArtProvider, wxHtmlEasyPrinting, wxBufferedPaintDC, wxTreeCtrl, wxFindReplaceData, wxGridCellEditor, wxListView, wxSplitterEvent, wxSashWindow, wxContextMenuEvent, wxLayoutAlgorithm, wxCheckListBox, wxGridCellBoolEditor, wxTopLevelWindow, wxMultiChoiceDialog, wxOverlay, wxTaskBarIcon, wxAuiDockArt, wxSizeEvent, wxComboBox, wxScrolledWindow, wxCommandEvent, wxPanel, wxDataObject, wxGraphicsPath, wxDatePickerCtrl, wxFocusEvent, wxGridCellChoiceEditor, wxImageList, wxToolTip, wxPalette, wxSlider, wxSizer, wxPasswordEntryDialog, wxSingleChoiceDialog, wxPen, wxBrush, wxAuiNotebook, wxMaximizeEvent, wxGridCellNumberEditor, wxPageSetupDialogData, wxSplashScreen, wxMenuBar, wxMemoryDC, wxToolbook, wxPopupTransientWindow, wxGCDC, wxAcceleratorEntry, wxRadioButton, wxPickerBase, wxCloseEvent, wxNotebook, wxAcceleratorTable, wxCaret, wxMDIParentFrame, wxPreviewControlBar, wxHelpEvent, wxSpinButton, wxGenericDirCtrl, wxFont, wxControlWithItems, wxSystemSettings, wxWindow, wxTreeEvent, wxSpinEvent, wxFontPickerEvent, wxAuiTabArt, wxIconBundle, wxClientDC, wxAuiManagerEvent, wxPageSetupDialog, wxSashLayoutWindow, wxGridEvent, wxGridCellStringRenderer, wxPreviewCanvas, wxTextAttr, glu, gl,
    %% Handcrafted modules
    wx,
    wx_object,
    wxe_master,
    wxe_server,
    wxe_util
   ]},
  {registered, []},
  {applications, [stdlib, kernel]},
  {env, []},
  {runtime_dependencies, ["stdlib-2.0","kernel-3.0","erts-6.0"]}
 ]}.
